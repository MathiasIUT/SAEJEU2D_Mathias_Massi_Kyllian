package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.input.KeyCode;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
//import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;

import java.util.HashSet;
import java.util.Set;

public class GameLoop {

    private Timeline gameLoop;
    private int temps;
    private Joueur joueur;

    public GameLoop(Joueur joueur) {
        this.joueur = joueur;
    }

    public void initGameLoop() {
        gameLoop = new Timeline();
        temps = 0;
        gameLoop.setCycleCount(Timeline.INDEFINITE);
        KeyFrame kf = new KeyFrame(
                Duration.millis(20),
                (ev -> {
                    joueur.deplacer();
                    //pnj.deplacer();
                }));
        gameLoop.getKeyFrames().add(kf);
        gameLoop.play();
    }


}
