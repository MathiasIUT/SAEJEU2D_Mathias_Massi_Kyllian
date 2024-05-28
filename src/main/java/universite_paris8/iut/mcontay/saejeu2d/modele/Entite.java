package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.beans.property.SimpleIntegerProperty;

public abstract class Entite {
    private String nom;
    private String ptsDeVie;
    private int id;
    private DoubleProperty positionX;
    private DoubleProperty positionY;
    private IntegerProperty direction;
    private IntegerProperty nouvelleDirection;

    private Terrain terrain;

    private static final double MOVE_DISTANCE = 5;

    public Entite(Terrain terrain, String nom, String ptsDeVie, int id, double initialX, double initialY) {
        this.nom = nom;
        this.ptsDeVie = ptsDeVie;
        this.id = id;
        this.positionX = new SimpleDoubleProperty(initialX);
        this.positionY = new SimpleDoubleProperty(initialY);
        this.direction = new SimpleIntegerProperty(0);
        this.nouvelleDirection = new SimpleIntegerProperty(0);
        this.terrain = terrain;
    }

    public String getNom() {
        return nom;
    }

    public int getId() {
        return id;
    }

    public String getPtsDeVie() {
        return ptsDeVie;
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

    public IntegerProperty nouvelleDirectionProperty() {
        return nouvelleDirection;
    }

    public int getNouvelleDirection() {
        return nouvelleDirection.get();
    }

    public void setNouvelleDirection(int direction) {
        this.nouvelleDirection.set(direction);
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

    public void seDeplaceHaut() {
        double newY = getPositionY() - MOVE_DISTANCE;
        if (newY >= 0 && terrain.estAutorisee(getPositionX() + 24, newY)) {
            if (terrain.estAutorisee(getPositionX(), newY)) {
                setPositionY(newY);
            } else {
                System.out.println("Impossible de se déplacer vers le haut");
            }
        }
    }

    public void seDeplaceGauche() {
        double newX = getPositionX() - MOVE_DISTANCE;
        if (terrain.estAutorisee(newX, getPositionY() + 48)) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la gauche");
        }
    }

    public void seDeplaceBas() {
        double newY = getPositionY() + MOVE_DISTANCE;
        if (terrain.estAutorisee(getPositionX() + 24  , newY + 48)) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le bas");
        }
    }

    public void seDeplaceDroite() {
        double newX = getPositionX() + MOVE_DISTANCE;
        if (terrain.estAutorisee(newX + 48, getPositionY() + 48)) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la droite");
        }
    }

    public void deplacer() {
        switch (getDirection()) {
            case 0:
                break;
            case 1:
                seDeplaceHaut();
                break;
            case 2:
                seDeplaceDroite();
                break;
            case 3:
                seDeplaceBas();
                break;
            case 4:
                seDeplaceGauche();
                break;
        }
    }

    public void changerDirection() {
        if (getNouvelleDirection() != getDirection()) {
            setDirection(getNouvelleDirection());
        }
    }
}
