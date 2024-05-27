package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.IntegerProperty;
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
    private int direction ;
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

    public void deplacementHaut(){
        direction = 1;
    }
    public void deplacementDroite(){
        direction = 2;
    }
    public void deplacementBas(){
        direction = 3;
    }
    public void deplacementGauche(){
        direction = 4;
    }


    private void seDeplaceHaut() {
        double newY = getPositionY() - MOVE_DISTANCE;
        if (newY >= 0 && terrain.estAutorisee(getPositionX()+16, newY)) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le haut");
        }
    }

    private void seDeplaceGauche() {
        double newX = getPositionX() - MOVE_DISTANCE;
        if (newX >= 0 && terrain.estAutorisee(newX, getPositionY())) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la gauche");
        }
    }

    private void seDeplaceBas() {
        double newY = getPositionY() + MOVE_DISTANCE;
        if (newY >= 0 && terrain.estAutorisee(getPositionX()+16, newY+16)) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le bas");
        }
    }

    private void seDeplaceDroite() {
        double newX = getPositionX() + MOVE_DISTANCE;
        if (newX >= 0 && terrain.estAutorisee(newX+32, getPositionY())) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la droite");
        }
    }

    public void deplacement(){
        switch (direction){
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