package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;

import java.util.HashSet;
import java.util.Set;

public class GameLoop {

    private Timeline gameLoop ;
    private int temps ;
    private Set<KeyCode> keysPressed = new HashSet<>();
    private Joueur joueur ;
    private JoueurVue joueurVue ;

    public GameLoop(Joueur joueur) {
        this.joueur = joueur;
        this.joueurVue = joueurVue;
        this.keysPressed = keysPressed;
    }

    public void initGameLoop() {
        gameLoop = new Timeline();
        temps = 0;
        gameLoop.setCycleCount(Timeline.INDEFINITE);
        KeyFrame kf = new KeyFrame(
                Duration.millis(20),
                (ev -> {
                    //update();
                    // env.unTour();
                    joueur.deplacement();
                }));
        gameLoop.getKeyFrames().add(kf);
        gameLoop.play();
    }

    private void update() {
        boolean enMouvement = false;


        if (keysPressed.contains(KeyCode.Z)) {
            joueur.seDeplaceHaut();
            joueurVue.changerImage(KeyCode.Z);
            enMouvement = true;
        }
        if (keysPressed.contains(KeyCode.Q)) {
            joueur.seDeplaceGauche();
            joueurVue.changerImage(KeyCode.Q);
            enMouvement = true;
        }
        if (keysPressed.contains(KeyCode.S)) {
            joueur.seDeplaceBas();
            joueurVue.changerImage(KeyCode.S);
            enMouvement = true;
        }
        if (keysPressed.contains(KeyCode.D)) {
            joueur.seDeplaceDroite();
            joueurVue.changerImage(KeyCode.D);
            enMouvement = true;
        }


        double x = joueur.getPositionX();
        double y = joueur.getPositionY();
        joueurVue.VuePositionJoueur(x, y);
    }


}
