package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.beans.binding.Bindings;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.vue.VieVue;

import java.io.IOException;

public class Vie {
    private VieVue vieVue;
    private Joueur joueur;
    private Terrain terrain;
    private Image[] imagesVie;
    private ImageView vieView;
    private Rectangle fond;
    private Rectangle barre;

    public Vie(Pane pane, Joueur joueur) {
    }


    public void infligerDegats(int degats) {
        joueur.setVie(joueur.getVie() - degats);
        System.out.println("Dégâts infligés: " + degats + ". Vie restante du joueur: " + joueur.getVie());
        if (joueur.getVie() == 0) {
            vieVue.afficherEcranMort();
        }
    }

//    private void initTimeline() {
//        Timeline timeline = new Timeline(new KeyFrame(Duration.seconds(5), event -> {
//            int x = (int) joueur.getPositionX();
//            int y = (int) joueur.getPositionY() ;
//            if (terrain.codesTuiles[((y)/32)][((x)/32)] == 3) {
//                joueur.setVie(joueur.getVie() - 35);
//            }
//        }));
//        timeline.setCycleCount(Timeline.INDEFINITE);
//        timeline.play();
//    }
}
//        this.joueur = joueur;
//
//        chargerImages();
//
//        vieView = new ImageView(imagesVie[0]);
//        vieView.setFitHeight(48);
//        vieView.setFitWidth(48);
//        pane.getChildren().add(vieView);
//
//        joueur.vieProperty().addListener((observable, oldValue, newValue) -> {
//            mettreAJourImageVie();
//        });
//
//        vieView.setLayoutX(10);
//        vieView.setLayoutY(10);
//
//        Timeline timeline = new Timeline(new KeyFrame(Duration.seconds(10), event -> {
//            joueur.perdreVie(10);
//        }));
//        timeline.setCycleCount(Timeline.INDEFINITE);
//        timeline.play();
//
//        // Création du fond de la barre de vie
//        fond = new Rectangle(200, 20);
//        fond.setFill(Color.GRAY);
//
//        // Création de la barre de vie
//        barre = new Rectangle(200, 20);
//        barre.setFill(Color.RED);
//
//        // Placement initial de la barre de vie
//        pane.getChildren().addAll(fond, barre);
//
//        // Liaison de la longueur de la barre de vie aux points de vie du joueur
//        barre.widthProperty().bind(Bindings.createDoubleBinding(() ->
//                200.0 * joueur.getVie() / joueur.getVieMax(), joueur.vieProperty(), joueur.vieMaxProperty()));
//
//        // Placement de la barre de vie en haut à gauche du pane
//        fond.setTranslateX(10);
//        fond.setTranslateY(10);
//        barre.setTranslateX(10);
//        barre.setTranslateY(10);
//    }
//
//    public void mettreAJour() {
//        double viePourcentage = (double) joueur.getVie() / joueur.getVieMax();
//        barre.setWidth(viePourcentage * 200);
//    }
//
//
//
//
//
//    private void chargerImages() {
//        try {
//            imagesVie = new Image[]{
//                    new Image(Lanceur.class.getResource("Coeur-100%.png").openStream()),
//                    new Image(Lanceur.class.getResource("Coeur-75%.png").openStream()),
//                    new Image(Lanceur.class.getResource("Coeur-50%.png").openStream()),
//                    new Image(Lanceur.class.getResource("Coeur-25%.png").openStream()),
//                    new Image(Lanceur.class.getResource("Coeur-0%.png").openStream())
//            };
//        } catch (IOException e) {
//            throw new RuntimeException(e);
//        }
//    }
//
//
//    public void mettreAJourImageVie() {
//
//        int vieActuelle = joueur.getVie();
//
//        if (vieActuelle > 75) {
//            vieView.setImage(imagesVie[0]);
//        } else if (vieActuelle > 50) {
//            vieView.setImage(imagesVie[1]);
//        } else if (vieActuelle > 25) {
//            vieView.setImage(imagesVie[2]);
//        } else if (vieActuelle > 0) {
//            vieView.setImage(imagesVie[3]);
//        }
//        else if (vieActuelle == 0) {
//            vieView.setImage(imagesVie[4]);
//        }
//    }


