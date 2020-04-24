package serverPackage;

import java.io.DataInputStream;
import java.io.IOException;
import java.io.PrintStream;
import java.net.Socket;

class ClientThread extends Thread {

	public String clientName = null;
	private DataInputStream in = null;
	private PrintStream out = null;
	private Socket clientSocket = null;
	private final ClientThread[] clients;
	private int maxClients;

	public ClientThread(Socket clientSocket, ClientThread[] clients) {
		this.clientSocket = clientSocket;
		this.clients = clients;
		maxClients = clients.length;
	}

	/*
	 * supprimer du tableau "clients" un client qui aurait été déconnecté de manière
	 * innatendue et avertir les autres utilisateurs
	 */
	public void preventUnexpectedInterruption(ClientThread client) {
		for (int i = 0; i < maxClients; i++) {
			if (clients[i] != null && clients[i].clientName != null) {
				clients[i].out.println("** L'utilisateur " + client.clientName + " a été déconnecté **");
				if (clients[i] == client) {
					clients[i] = null;
				}
			}
		}

	}

	public void run() {
		int maxClients = this.maxClients;
		ClientThread[] clients = this.clients;

		try {
			/*
			 * Créer les streams d'entrée et de sortie pour ce client
			 */
			in = new DataInputStream(clientSocket.getInputStream());
			out = new PrintStream(clientSocket.getOutputStream());
			String name;

			/* s'assurer de l'unicité et de la validité du pseudo */
			while (true) {
				out.println("ENTREZ VOTRE PSEUDO : ");
				name = in.readLine().trim();
				if (name == null || name.isEmpty() || name.trim().isEmpty()) {
					out.println("\nCeci n'est pas un pseudo\n");
				} else {
					int i = 0;
					for (i = 0; i < maxClients; i++) {
						if (clients[i] != null && clients[i] != this && clients[i].clientName != null) {
							if (name.contentEquals(clients[i].clientName)) {
								out.println("\nCe pseudo est déjà pris\n");
								break;
							}

						}
					}
					if (i == maxClients) {
						break;
					}
				}
			}

			/*
			 * Souhaiter la bienvenu au nouvel utilisateur et l'annoncer aux autres
			 * utilisateurs
			 */
			out.println("Vous avez rejoint la conversation\nEntrez \"exit\" pour quitter");
			synchronized (this) {
				for (int i = 0; i < maxClients; i++) {
					if (clients[i] != null && clients[i] == this) {
						clientName = name;
						break;
					}
				}
				for (int i = 0; i < maxClients; i++) {
					if (clients[i] != null && clients[i] != this) {
						clients[i].out.println("** " + name + " a rejoint la conversation **");
					}
				}
			}
			/* Démarrer la conversation */
			while (true) {
				String line = in.readLine();
				if (line.startsWith("exit")) {
					break;
				}

				/* Partager le message aux autres utilisateurs */
				synchronized (this) {
					for (int i = 0; i < maxClients; i++) {
						if (clients[i] != null && clients[i] != this && clients[i].clientName != null) {
							clients[i].out.println(name + " : " + line);
						}
					}
				}
			}
			/*
			 * prévenir le thread de surveillance de connexion inatendue que la déconnexion
			 * est normale
			 */
			synchronized (this) {
				UnexpectedClientInterruption leftClient = new UnexpectedClientInterruption(this);
				for (int i = 0; i < maxClients; i++) {
					if (clients[i] == this) {
						leftClient.stopThread(clients[i]);
					}
				}
			}

			synchronized (this) {
				for (int i = 0; i < maxClients; i++) {
					if (clients[i] != null && clients[i] != this && clients[i].clientName != null) {
						clients[i].out.println("** L'utilisateur " + name + " a quitté la conversation **");
					}
				}
			}
			out.println("** Au revoir " + name + " **");
			/*
			 * Nettoyer le client dans le tableau "clients" pour qu'un nouveau client puisse
			 * être accepté par le serveur
			 */
			synchronized (this) {
				for (int i = 0; i < maxClients; i++) {
					if (clients[i] == this) {
						clients[i] = null;
					}
				}
			}
			/*
			 * Fermer les streams d'entrée, de sortie et le socket
			 */
			in.close();
			out.close();
			clientSocket.close();
		} catch (IOException e) {
		}
	}
}