package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;


public class Joueur {
    private DoubleProperty positionX;
    private DoubleProperty positionY;
    public static Joueur joueur ;
    private static final double MOVE_DISTANCE = 5;

    public Joueur(double initialX, double initialY) {
        positionX = new SimpleDoubleProperty(initialX);
        positionY = new SimpleDoubleProperty(initialY);
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

    public void seDeplaceHaut () {
        if (positionY.getValue() == 0 ) {
            System.out.println("Impossible de se déplacer");
        }
        else {
            setPositionY(getPositionY() - 10);
        }
    }

    public void seDeplaceGauche () {
        if (positionX.getValue() == 0 ) {
            System.out.println("Impossible de se déplacer");
        }
        else {
            setPositionX(getPositionX() - 10);
        }
    }
    public void seDeplaceBas () {
        if (positionY.getValue() > 483) {
            System.out.println("Impossible de se déplacer");
        }
        else {
            setPositionY(getPositionY() + 10);
        }
    }

    public void seDeplaceDroite () {
        if (positionX.getValue() > 489) {
            System.out.println("Impossible de se déplacer");
        }
        else {
            setPositionX(getPositionX() + 10);
        }
    }
}


//    public void mouvement(KeyEvent e) {
//        if (e.getCode() == KeyCode.Z) {
//            joueur.setPositionY(joueur.getPositionY() - MOVE_DISTANCE);
//        } else if (e.getCode() == KeyCode.Q) {
//            joueur.setPositionX(joueur.getPositionX() - MOVE_DISTANCE);
//        } else if (e.getCode() == KeyCode.S) {
//            joueur.setPositionY(joueur.getPositionY() + MOVE_DISTANCE);
//        } else if (e.getCode() == KeyCode.D) {
//            joueur.setPositionX(joueur.getPositionX() + MOVE_DISTANCE);
//        }