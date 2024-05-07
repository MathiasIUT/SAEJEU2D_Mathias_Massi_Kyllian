package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;

public class Sprite {
    private DoubleProperty positionX;
    private DoubleProperty positionY;

    public Sprite(double initialX, double initialY) {
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
}
