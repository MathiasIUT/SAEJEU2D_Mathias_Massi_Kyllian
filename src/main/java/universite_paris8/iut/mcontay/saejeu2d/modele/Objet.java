package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.scene.image.Image;

/*La classe Objet représente un objet du jeu avec des attributs comme le nom, la description, les dégâts, l'image, et la position (x, y).
 Le constructeur initialise ces attributs et tente de charger l'image à partir d'un chemin donné.
 Les méthodes getter et setter permettent d'accéder et de modifier les propriétés de l'objet. */

public class Objet {
    private String nom;
    private String description;
    private Terrain terrain;
    private Image image;
    private int degats;
    private double positionX;
    private double positionY;

    public Objet(String nom, String description, Terrain terrain, int degats, String imagePath, double x, double y) {
        this.terrain = terrain;
        this.positionX = x;
        this.positionY = y;
        this.nom = nom;
        this.description = description;
        this.degats = degats;
        try {
            this.image = new Image(getClass().getResourceAsStream(imagePath));
        } catch (Exception e) {
            System.out.println("Erreur lors du chargement de l'image : " + e.getMessage());
            this.image = null;
        }
    }

    public int getDegats() {
        return degats;
    }

    public void setDegats(int degats) {
        this.degats = degats;
    }

    public Image getImage() {
        return image;
    }

    public void setImage(Image image) {
        this.image = image;
    }

    public String getNom() {
        return nom;
    }

    public void setNom(String nom) {
        this.nom = nom;
    }

    public double getPositionX() {
        return positionX;
    }

    public void setPositionX(double positionX) {
        this.positionX = positionX;
    }

    public double getPositionY() {
        return positionY;
    }

    public void setPositionY(double positionY) {
        this.positionY = positionY;
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
