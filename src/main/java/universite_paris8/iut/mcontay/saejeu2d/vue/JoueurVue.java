package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import javafx.animation.PauseTransition;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Environnement;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Monstre;
import java.io.IOException;

/* La classe JoueurVue gère l'affichage et les animations du joueur, y compris ses mouvements et ses attaques,
    en changeant l'image affichée en fonction de la direction et de l'état du joueur. Elle lie les propriétés de position du joueur à l'interface
    et supprime la vue lorsque le joueur meurt. Les animations d'attaque sont temporisées avec une pause pour simuler l'action.*/

public class JoueurVue {

    private ImageView spriteView;
    private Environnement env;
    private Image[] imagesMarcheBas;
    private Image[] imagesMarcheHaut;
    private Image[] imagesMarcheGauche;
    private Image[] imagesMarcheDroite;
    private Image[] imagesAttaque;

    private int indexFrame = 0;
    private int frameDelay = 5;
    private boolean isAttacking = false;

    public JoueurVue(Pane pane, Environnement env) {
        this.env = env;
        Joueur joueur = env.getJoueur();
        chargerImages();

        spriteView = new ImageView(imagesMarcheBas[0]);
        spriteView.setFitHeight(16);
        spriteView.setFitWidth(16);
        spriteView.translateXProperty().bind(joueur.positionXProperty());
        spriteView.translateYProperty().bind(joueur.positionYProperty());
        pane.getChildren().add(spriteView);

        joueur.directionProperty().addListener((observable, oldValue, newValue) -> {
            if (!isAttacking) {
                changerImage((int) newValue);
            }
        });

        env.nbToursProperty().addListener((observable, oldValue, newValue) -> {
            if ((int) newValue % frameDelay == 0 && !isAttacking) {
                indexFrame = (indexFrame + 1) % 3;
                changerImage(joueur.getDirection());
            }
        });

        joueur.estMortProperty().addListener((observable, oldValue, newValue) -> {
            if (newValue) {
                supprimerVue();
            }
        });
    }

    private void chargerImages() {
        imagesMarcheBas = new Image[]{
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurDeboutFace.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceFace2.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceFace.png"))
        };
        imagesMarcheHaut = new Image[]{
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurDeboutDos.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceDos.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceDos2.png"))
        };
        imagesMarcheGauche = new Image[]{
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurDeboutGauche.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceGauche.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceGauche2.png"))
        };
        imagesMarcheDroite = new Image[]{
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurDeboutDroit.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceDroite.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAvanceDroite2.png"))
        };
        imagesAttaque = new Image[]{
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAttaqueHaut.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAttaqueBas.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAttaqueDroite.png")),
            new Image(Lanceur.class.getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/joueur/joueurAttaqueGauche.png"))
        };
    }

    public void changerImage(int direction) {
        switch (direction) {
            case 1 -> spriteView.setImage(imagesMarcheHaut[indexFrame]);
            case 2 -> spriteView.setImage(imagesMarcheDroite[indexFrame]);
            case 3 -> spriteView.setImage(imagesMarcheBas[indexFrame]);
            case 4 -> spriteView.setImage(imagesMarcheGauche[indexFrame]);
            default -> spriteView.setImage(imagesMarcheBas[0]);
        }
    }

    public void VueAttaque(int direction) {
        isAttacking = true;
        switch (direction) {
            case 1 -> spriteView.setImage(imagesAttaque[0]);
            case 2 -> spriteView.setImage(imagesAttaque[2]);
            case 3 -> spriteView.setImage(imagesAttaque[1]);
            case 4 -> spriteView.setImage(imagesAttaque[3]);
            default -> spriteView.setImage(imagesAttaque[1]);
        }
        Joueur joueur = env.getJoueur();
        Monstre monstre = env.getMonstre();
        joueur.attaquer(monstre);

        PauseTransition pause = new PauseTransition(Duration.millis(250));
        pause.setOnFinished(event -> {
            isAttacking = false;
            changerImage(direction);
        });
        pause.play();
    }

    public void supprimerVue() {
        Pane parent = (Pane) spriteView.getParent();
        if (parent != null) {
            parent.getChildren().remove(spriteView);
        }
    }
}
