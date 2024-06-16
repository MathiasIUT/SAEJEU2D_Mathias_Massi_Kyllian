package universite_paris8.iut.mcontay.saejeu2d.util;

import java.util.ArrayList;
import java.util.List;

/* La classe Chunk représente une section de données d'un jeu avec des propriétés pour sa hauteur, largeur, et position (x, y),
 ainsi qu'une liste de données (data). Elle fournit des getters et setters pour accéder et modifier ces propriétés.*/

public class Chunk {
    private List<Integer> data = new ArrayList<>();
    private int height;
    private int width;
    private int x;
    private int y;

    // Getters and setters...

    public List<Integer> getData() {
        return data;
    }

    public void setData(List<Integer> data) {
        this.data = data;
    }

    public int getHeight() {
        return height;
    }

    public void setHeight(int height) {
        this.height = height;
    }

    public int getWidth() {
        return width;
    }

    public void setWidth(int width) {
        this.width = width;
    }

    public int getX() {
        return x;
    }

    public void setX(int x) {
        this.x = x;
    }

    public int getY() {
        return y;
    }

    public void setY(int y) {
        this.y = y;
    }
}
