package clientPackage;

import java.io.DataInputStream;
import java.io.PrintStream;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.IOException;
import java.net.Socket;
import java.net.UnknownHostException;

public class Client implements Runnable {

	private static Socket clientSocket = null;
	private static PrintStream out = null;
	private static DataInputStream in = null;

	private static BufferedReader inputLine = null;
	private static boolean closed = false;

	public static void main(String[] args) {
		int portNumber = 3333;
		String host = "localhost";

		if (args.length < 2) {
			System.out.println("Connexion au serveur réussie\n" + "host=" + host + ", port=" + portNumber);
		} else {
			host = args[0];
			portNumber = Integer.valueOf(args[1]).intValue();
		}

		/*
		 * Ouverture d'un socket sur le port et le host mentionné plus haut. Ouverture
		 * du stream d'entrée et de sortie
		 */
		try {
			clientSocket = new Socket(host, portNumber);
			inputLine = new BufferedReader(new InputStreamReader(System.in));
			out = new PrintStream(clientSocket.getOutputStream());
			in = new DataInputStream(clientSocket.getInputStream());
		} catch (UnknownHostException e) {
			System.err.println(host + " : host inconnu ");
		} catch (IOException e) {
			System.err.println("IOException: " + e);
		}

		/*
		 * Si tout est initialisé nous allons pouvoir écrire sur le socket
		 */
		if (clientSocket != null && out != null && in != null) {
			try {

				/* Création d'un thread pour lire sur le serveur */
				new Thread(new Client()).start();
				while (!closed) {
					out.println(inputLine.readLine().trim());
				}
				/* Fermerture des streams d'entrée et de sortie et du socket */
				out.close();
				in.close();
				clientSocket.close();
			} catch (IOException e) {
				System.err.println("IOException:  " + e);
			}
		}
	}

	/* méthode run du thread pour l'exécuter */
	public void run() {
		/*
		 * Lecture sur le serveur jusqu'à recevoir "**Au revoir " du serveur. On sort de
		 * la boucle lorsqu'on l'a reçu pour pouvoir fermer le socket plus haut
		 */
		String responseLine;
		try {
			while ((responseLine = in.readLine()) != null) {
				System.out.println(responseLine);
				if (responseLine.indexOf("** Au revoir ") != -1)
					break;
			}
			closed = true;
		} catch (IOException e) {
			System.err.println("IOException:  " + e);
		}
	}
}
