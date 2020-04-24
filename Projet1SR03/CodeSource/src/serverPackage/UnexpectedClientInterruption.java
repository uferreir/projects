package serverPackage;

class UnexpectedClientInterruption extends Thread {

	private final ClientThread client;
	private static boolean userHasLeft;

	public UnexpectedClientInterruption(ClientThread client) {
		this.client = client;
	}

	/* arrêter le thread si le client a quitté de lui-même la conversation */
	public void stopThread(ClientThread client) {
		if (client == this.client) {
			userHasLeft = true;
		}
	}

	public void run() {
		ClientThread client = this.client;
		/* Boucler tant que le client n'est pas déconnecté */
		while (true) {
			/* le client a été déconnecté de manière inatendue */
			if (!client.isAlive() && !userHasLeft) {
				synchronized (this) {
					client.preventUnexpectedInterruption(client);
					break;
				}
				/* le client a quitté de lui-même la conversation */
			} else if (!client.isAlive() && userHasLeft) {
				userHasLeft = !userHasLeft;
				break;
			}
		}
	}
}