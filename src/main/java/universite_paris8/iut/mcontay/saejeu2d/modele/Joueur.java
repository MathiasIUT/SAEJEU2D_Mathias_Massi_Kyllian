package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Joueur {

    private Terrain terrain;
    private DoubleProperty positionX;
    private DoubleProperty positionY;
    private IntegerProperty direction;
    private IntegerProperty nouvelleDirection;

    private static final double MOVE_DISTANCE = 5;

    public Joueur(Terrain terrain, double initialX, double initialY) {
        this.terrain = terrain;
        this.positionX = new SimpleDoubleProperty(initialX);
        this.positionY = new SimpleDoubleProperty(initialY);
        this.direction = new SimpleIntegerProperty(0);
        this.nouvelleDirection = new SimpleIntegerProperty(0);
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
        setNouvelleDirection(1);
    }

    public void deplacementDroite() {
        setNouvelleDirection(2);
    }

    public void deplacementBas() {
        setNouvelleDirection(3);
    }

    public void deplacementGauche() {
        setNouvelleDirection(4);
    }

    public void deplacementStop() {
        setNouvelleDirection(0);
    }

    public void seDeplaceHaut() {
        double newY = getPositionY() - MOVE_DISTANCE;
        if (newY >= 0 && terrain.estAutorisee(getPositionX() + 16, newY)) {
            if (terrain.estAutorisee(getPositionX(), newY)) {
                setPositionY(newY);
            } else {
                System.out.println("Impossible de se déplacer vers le haut");
            }
        }
    }

    public void seDeplaceGauche() {
        double newX = getPositionX() - MOVE_DISTANCE;
        if (terrain.estAutorisee(newX, getPositionY() + 16)) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la gauche");
        }
    }

    public void seDeplaceBas() {
        double newY = getPositionY() + MOVE_DISTANCE;
        if (terrain.estAutorisee(getPositionX() + 32, newY + 32)) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le bas");
        }
    }

    public void seDeplaceDroite() {
        double newX = getPositionX() + MOVE_DISTANCE;
        if (terrain.estAutorisee(newX + 32, getPositionY() + 32)) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la droite");
        }
    }

    public void deplacement() {
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
