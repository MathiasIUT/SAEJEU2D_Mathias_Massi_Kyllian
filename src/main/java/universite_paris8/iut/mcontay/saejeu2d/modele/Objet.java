package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.scene.image.Image;

public class Objet {
    private String nom;
    private String description;
    private Image image;



    public Objet(String nom, String description, String imagePath) {

        this.nom = nom;
        this.description = description;
        try {
            this.image = new Image(getClass().getResourceAsStream(imagePath));
        } catch (Exception e) {
            System.out.println("Erreur lors du chargement de l'image : " + e.getMessage());
            this.image = null; // ou une image par défaut
        }

    }



    public Image getImage() {return image;}
    public void setImage(Image image) {
        this.image = image;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public String getDescription() {
        return description;
    }

    public void setDescription(String description) {
        this.description = description;
    }

    @Override
    public String toString() {
        return nom + ": " + description;
    }
}
