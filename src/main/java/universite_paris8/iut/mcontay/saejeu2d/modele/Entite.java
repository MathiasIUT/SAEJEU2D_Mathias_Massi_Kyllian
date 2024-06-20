package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.beans.property.SimpleIntegerProperty;

/* La classe abstraite Entite représente une entité dans le jeu avec des propriétés pour le nom, les points de vie, la position, la direction, et le mouvement.
 Elle fournit des méthodes pour déplacer l'entité dans différentes directions et pour gérer les points de vie.
 Les mouvements sont contrôlés par des méthodes spécifiques qui vérifient la validité des nouvelles positions sur le terrain avant de les mettre à jour. */

public abstract class Entite {

    private String nom;
    private IntegerProperty ptsDeVie;
    private DoubleProperty positionX;
    private DoubleProperty positionY;
    private IntegerProperty direction;
    protected Terrain terrain;
    private Environnement environnement;
    private int id;
    protected int moveDistance;

    public Entite(Environnement environnement, Terrain terrain, String nom, int ptsDeVie, double initialX, double initialY, int id, int moveDistance) {
        this.nom = nom;
        this.ptsDeVie = new SimpleIntegerProperty(ptsDeVie);
        this.positionX = new SimpleDoubleProperty(initialX);
        this.positionY = new SimpleDoubleProperty(initialY);
        this.direction = new SimpleIntegerProperty(0);
        this.environnement = environnement;
        this.id = id;
        this.moveDistance = moveDistance;
    }

    public String getNom() {
        return nom;
    }

    public IntegerProperty ptsDeVieProperty() {
        return ptsDeVie;
    }

    public int getPtsDeVie() {
        return ptsDeVie.get();
    }

    public void setPtsDeVie(int ptsDeVie) {
        this.ptsDeVie.set(Math.max(ptsDeVie, 0));
    }

    public DoubleProperty positionXProperty() {
        return positionX;
    }

    public DoubleProperty positionYProperty() {
        return positionY;
    }

    public double getPositionX() {
        return positionX.get();
    }

    public double getPositionY() {
        return positionY.get();
    }

    public void setPositionX(double x) {
        positionX.set(x);
    }

    public void setPositionY(double y) {
        positionY.set(y);
    }

    public IntegerProperty directionProperty() {
        return direction;
    }

    public int getDirection() {
        return direction.get();
    }

    public void setDirection(int direction) {
        this.direction.set(direction);
    }

    public Terrain getTerrain() {
        return terrain;
    }

    public int getId() {
        return id;
    }

    public Environnement getEnvironnement() {
        return environnement;
    }

    public void deplacementHaut() {
        setDirection(1);
    }

    public void deplacementDroite() {
        setDirection(2);
    }

    public void deplacementBas() {
        setDirection(3);
    }

    public void deplacementGauche() {
        setDirection(4);
    }

    public void deplacementStop() {
        setDirection(0);
    }

    public void enleverPV(int enleve) {
        setPtsDeVie(getPtsDeVie() - enleve);
        System.out.println(nom + " a perdu " + enleve + " points de vie. Il lui reste " + getPtsDeVie() + " points de vie.");
    }

    public void seDeplaceHaut() {
        double newY = getPositionY() - moveDistance;
        if (newY >= 0 && environnement.getTerrain().estAutorisee(getPositionX() + 8, newY)) {
            if (environnement.getTerrain().estAutorisee(getPositionX(), newY)) {
                setPositionY(newY);
            } else {
                System.out.println("Impossible de se déplacer vers le haut");
            }
        }
    }

    public void seDeplaceGauche() {
        double newX = getPositionX() - moveDistance;
        if (environnement.getTerrain().estAutorisee(newX, getPositionY() + 16)) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la gauche");
        }
    }

    public void seDeplaceBas() {
        double newY = getPositionY() + moveDistance;
        if (environnement.getTerrain().estAutorisee(getPositionX() + 8, newY + 16)) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le bas");
        }
    }

    public void seDeplaceDroite() {
        double newX = getPositionX() + moveDistance;
        if (environnement.getTerrain().estAutorisee(newX + 16, getPositionY() + 16)) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la droite");
        }
    }

//    public boolean enCollision(Rectangle hitbox) {
//        double deltaX = hitbox.getX() - this.getPositionX();
//        double deltaY = hitbox.getY() - this.getPositionY();
//        return Math.abs(deltaX) < 16 && Math.abs(deltaY) < 16; // Taille de la sprite (16*16)
//    }

    public void deplacer() {
        switch (getDirection()) {
            case 1 -> seDeplaceHaut();
            case 2 -> seDeplaceDroite();
            case 3 -> seDeplaceBas();
            case 4 -> seDeplaceGauche();
        }
    }
}
