package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.modele.Environnement;

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
