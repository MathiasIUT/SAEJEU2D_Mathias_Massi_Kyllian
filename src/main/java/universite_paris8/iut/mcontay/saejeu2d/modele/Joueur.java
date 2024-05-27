package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;


public class Joueur {

    private Terrain terrain;
    private DoubleProperty positionX;
    private DoubleProperty positionY;

    private IntegerProperty direction ;

    static final double MOVE_DISTANCE = 1.5 ;

    public Joueur(Terrain terrain, double initialX, double initialY) {
//        super(nom, ptsDeVie, id);
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

    public int getDirection() {
        return direction.get();
    }

    public IntegerProperty directionProperty() {
        return direction;
    }

    public void seDeplacer(IntegerProperty direction){

    }

    public void seDeplaceHaut() {
        double newY = getPositionY() - MOVE_DISTANCE;
        if (terrain.estAutorisee(getPositionX(), newY)) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le haut");
        }
    }

    public void seDeplaceGauche() {
        double newX = getPositionX() - MOVE_DISTANCE;
        if (terrain.estAutorisee(newX,getPositionY() + 16 ) ) {
            setPositionX(newX);
        } else {
            System.out.println("Impossible de se déplacer vers la gauche");
        }
    }

    public void seDeplaceBas() {
        double newY = getPositionY() + MOVE_DISTANCE;
        if (terrain.estAutorisee(getPositionX() , newY+32)) {
            setPositionY(newY);
        } else {
            System.out.println("Impossible de se déplacer vers le bas");
        }
    }

    public void seDeplaceDroite() {
        double newX = getPositionX() + MOVE_DISTANCE ;
        if (terrain.estAutorisee(newX + 32, getPositionY() + 32 )) {
            setPositionX(newX);

        } else {
            System.out.println("Impossible de se déplacer vers la droite");
        }
    }



}
