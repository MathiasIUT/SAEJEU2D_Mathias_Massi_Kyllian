package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.modele.Environnement;

/* La classe GameLoop gère la boucle principale du jeu, utilisant un Timeline pour exécuter des actions de manière répétée.
 Le constructeur initialise l'environnement du jeu et une tâche de mise à jour.
  La méthode initGameLoop configure la boucle de jeu pour qu'elle s'exécute indéfiniment avec des intervalles de 20 millisecondes, effectue un tour de jeu,
 et appelle la tâche de mise à jour. Si le tour de jeu indique que le jeu est terminé, la boucle s'arrête. */

public class GameLoop {

    private Timeline gameLoop;
    private Environnement environnement;
    private Runnable miseAJour;

    public GameLoop(Environnement env, Runnable miseAJour) {
        this.environnement = env;
        this.miseAJour = miseAJour;
    }

    public void initGameLoop() {
        gameLoop = new Timeline();
        gameLoop.setCycleCount(Timeline.INDEFINITE);
        KeyFrame kf = new KeyFrame(Duration.millis(20), ev -> {
            if (!environnement.faireUnTour()) gameLoop.stop();
            miseAJour.run();
        });

        gameLoop.getKeyFrames().add(kf);
        gameLoop.play();
    }
}
