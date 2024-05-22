package universite_paris8.iut.mcontay.saejeu2d.modele;


import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.controleur.Controleur;

public class GameLoop {

    private Joueur joueur ;

    private Controleur controleur ;

    private double dernierMouvement ;

    private final double DELAI_MOUVEMENT = 25_000_000 ;

    private Timeline gameLoop;

    public GameLoop () {
    }

    public void initAnimation() {
        gameLoop = new Timeline();
        gameLoop.setCycleCount(Timeline.INDEFINITE);

        KeyFrame kf = new KeyFrame(
                // on définit le FPS (nbre de frame par seconde)
                Duration.seconds(0.017),
                // on définit ce qui se passe à chaque frame
                // c'est un eventHandler d'ou le lambda
                (ev ->{

//                        System.out.println("un tour");
//                        joueur.setPositionX(joueur.getPositionX()+5);
//                        joueur.setPositionY(joueur.getPositionY()+5);

                })
        );
        gameLoop.getKeyFrames().add(kf);
        gameLoop.play();
    }



}
