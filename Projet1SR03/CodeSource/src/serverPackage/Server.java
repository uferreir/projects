package serverPackage;

import java.io.DataInputStream;
import java.io.PrintStream;
import java.io.IOException;
import java.net.Socket;
import java.net.ServerSocket;

public class Server {

	private static ServerSocket serverSocket = null;
	private static Socket clientSocket = null;

	/* le chat accepte un maximum de maxClients utilisateurs */
	private static final int maxClients = 2;
	private static final ClientThread[] clients = new ClientThread[maxClients];

	public static void main(String args[]) {

		int portNumber = 3333;
		if (args.length < 1) {
			System.out.println("Le serveur a �t� lanc� avec succ�s\n");
		} else {
			portNumber = Integer.valueOf(args[0]).intValue();
		}

		/* Ouverture d'un socket sur le port portNumber */
		try {
			serverSocket = new ServerSocket(portNumber);
		} catch (IOException e) {
			System.out.println(e);
		}

		/*
		 * Creation d'un socket client pour chaque connexion. Ce socket est pass� � un
		 * thread pour envoyer/recevoir des messages et un thread qui v�rifie que le
		 * client fonctionne correctement
		 */
		while (true) {
			try {
				clientSocket = serverSocket.accept();
				int i = 0;
				for (i = 0; i < maxClients; i++) {
					if (clients[i] == null) {
						(clients[i] = new ClientThread(clientSocket, clients)).start();
						new UnexpectedClientInterruption(clients[i]).start();
						break;
					}
				}
				/*
				 * si on tente de connecter plus de clients qu'autoris� on re�oit un message
				 * d'erreur
				 */
				if (i == maxClients) {
					PrintStream out = new PrintStream(clientSocket.getOutputStream());
					out.println("** Le serveur est plein, vous ne pouvez pas rejoindre la conversation **");
					out.close();
					clientSocket.close();
				}
			} catch (IOException e) {
				System.out.println(e);
			}
		}
	}
}