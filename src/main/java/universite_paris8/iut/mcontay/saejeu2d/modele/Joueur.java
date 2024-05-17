package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;


public class Joueur {

    private Terrain terrain;
    private DoubleProperty positionX;
    private DoubleProperty positionY;

    private static final double MOVE_DISTANCE = 5;

    public Joueur(Terrain terrain, double initialX, double initialY) {
        this.terrain = terrain;
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


    public void seDeplaceHaut() {
        double newY = getPositionY() - MOVE_DISTANCE;
        if (newY >= 0 && terrain.estAutorisee(getPositionX(), newY, "Haut")) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le haut");
        }
    }

    public void seDeplaceGauche() {
        double newX = getPositionX() - MOVE_DISTANCE;
        if (newX >= 0 && terrain.estAutorisee(newX, getPositionY(), "Gauche")) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la gauche");
        }
    }

    public void seDeplaceBas() {
        double newY = getPositionY() + MOVE_DISTANCE;
        if (newY >= 0 && terrain.estAutorisee(getPositionX(), newY, "Bas")) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le bas");
        }
    }

    public void seDeplaceDroite() {
        double newX = getPositionX() + MOVE_DISTANCE;
        if (newX >= 0 && terrain.estAutorisee(newX, getPositionY(), "Droite")) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la droite");
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