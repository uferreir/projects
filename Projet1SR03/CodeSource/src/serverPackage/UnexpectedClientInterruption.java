package serverPackage;

class UnexpectedClientInterruption extends Thread {

	private final ClientThread client;
	private static boolean userHasLeft;

	public UnexpectedClientInterruption(ClientThread client) {
		this.client = client;
	}

	/* arr�ter le thread si le client a quitt� de lui-m�me la conversation */
	public void stopThread(ClientThread client) {
		if (client == this.client) {
			userHasLeft = true;
		}
	}

	public void run() {
		ClientThread client = this.client;
		/* Boucler tant que le client n'est pas d�connect� */
		while (true) {
			/* le client a �t� d�connect� de mani�re inatendue */
			if (!client.isAlive() && !userHasLeft) {
				synchronized (this) {
					client.preventUnexpectedInterruption(client);
					break;
				}
				/* le client a quitt� de lui-m�me la conversation */
			} else if (!client.isAlive() && userHasLeft) {
				userHasLeft = !userHasLeft;
				break;
			}
		}
	}
}