package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.collections.ListChangeListener;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import javafx.scene.layout.VBox;
import javafx.scene.paint.Color;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import universite_paris8.iut.mcontay.saejeu2d.modele.*;

public class InventaireVue {
    private Environnement environnement;
    private Inventaire inventaire;
    private Pane pane;

    private Epee epee;
    private Joueur joueur;
    private Monstre monstre;
    private VBox inventaireBox;

    public InventaireVue(Pane pane, Inventaire inventaire, Joueur joueur, Epee epee, Environnement environnement) {
        this.pane = pane;
        this.inventaire = inventaire;
        this.joueur = joueur;
        this.epee = epee;
        this.environnement = environnement;
        this.monstre = environnement.getMonstre();

        this.inventaire.getObjets().addListener((ListChangeListener<? super Objet>) change -> {
            while (change.next()) {
                if (change.wasAdded()) {
                    for (Objet nouvelObjet : change.getAddedSubList()) {
                        afficherObjet(nouvelObjet);
                    }
                } else if (change.wasRemoved()) {
                    for (Objet ancienObjet : change.getRemoved()) {
                        inventaire.retirerObjet(ancienObjet);
                    }
                }
            }
        });

        inventaireBox = new VBox(10);
        inventaireBox.setLayoutX(0);
        inventaireBox.setLayoutY(10);
        inventaireBox.setStyle("-fx-background-color: rgba(0, 0, 0, 0.5); -fx-padding: 10; -fx-border-color: white; -fx-border-width: 2;");
        pane.getChildren().add(inventaireBox);
    }

    public void afficherInventaire() {
        inventaireBox.getChildren().clear();
        for (Objet objet : inventaire.getObjets()) {
            afficherObjet(objet);
        }

        if (!pane.getChildren().contains(inventaireBox)) {
            pane.getChildren().add(inventaireBox);
        }
        inventaireBox.setVisible(true);
    }

    public void masquerInventaire() {
        inventaireBox.setVisible(false);
    }

    private void afficherObjet(Objet objet) {
        ImageView imageView = new ImageView(objet.getImage());
        imageView.setFitHeight(32);
        imageView.setFitWidth(32);
        imageView.setOnMouseClicked(event -> utiliserObjet(objet));

        Text nomObjet = new Text(objet.getNom());
        nomObjet.setFill(Color.WHITE);
        nomObjet.setFont(Font.font("Verdana", 14));

        VBox itemBox = new VBox(imageView, nomObjet);
        itemBox.setStyle("-fx-padding: 5; -fx-border-color: white; -fx-border-width: 1;");
        inventaireBox.getChildren().add(itemBox);
    }

    public void utiliserObjet(Objet objet) {
        if (!inventaire.contientObjet(objet)) {
            System.out.println("Vous devez ramasser l'objet avant de l'utiliser.");
            return;
        }

        System.out.println("Objet utilisé: " + objet.getNom());
        if (objet instanceof Epee) {
            if (joueur.estProche(monstre)) {
                monstre.enleverPV(objet.getDegats());
                if (monstre.getPtsDeVie() <= 0) {
                    monstre.setEstMort(true);
                    environnement.supprimerEntiteParId(monstre.getId());
                }
            } else {
                System.out.println("Le monstre est trop loin pour être attaqué.");
            }
        } else if (objet instanceof Bouclier) {
            joueur.ajouterPtsDeVie(25);
            updateInventaireAffichage();
        }
    }

    private void updateInventaireAffichage() {
        inventaireBox.getChildren().clear();
        for (Objet objet : inventaire.getObjets()) {
            afficherObjet(objet);
        }
    }

    public VBox getInventaireBox() {
        return inventaireBox;
    }
}
