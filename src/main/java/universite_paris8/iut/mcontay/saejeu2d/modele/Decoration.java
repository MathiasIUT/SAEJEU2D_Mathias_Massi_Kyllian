package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;

public class Decoration {
    private IntegerProperty x = new SimpleIntegerProperty();
    private IntegerProperty y = new SimpleIntegerProperty();
    protected Terrain terrain;
    private String nom;

    public Decoration(int x, int y, String nom,Terrain terrain) {
        this.x.set(x);
        this.y.set(y);
        this.nom=nom;
        this.terrain=terrain;
    }

    public IntegerProperty xProperty() {
        return x;
    }
    public IntegerProperty yProperty() {
        return y;
    }

    public int getX(){ return x.getValue();}
    public int getY(){ return y.getValue();}
    public void setX(int x){
        this.x.set(x);
    }
    public void setY(int y){
        this.y.set(y);
    }
}
