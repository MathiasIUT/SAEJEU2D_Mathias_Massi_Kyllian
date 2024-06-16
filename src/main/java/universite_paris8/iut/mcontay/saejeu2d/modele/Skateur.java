package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.scene.shape.Rectangle;

public class Skateur extends Entite {

    double limiteXgauche, limiteXdroit;

    public Skateur(Environnement environnement, Terrain terrain, String nom, int ptsDeVie, double initialX, double initialY, int id, int moveDistance) {
        super(environnement, terrain, nom, ptsDeVie, initialX + 100, initialY, id, moveDistance);
        this.limiteXgauche = initialX;
        this.limiteXdroit = initialX + 100;
        this.setDirection(4);
    }

    @Override
    public void deplacer() {
        if (this.getPositionX() <= limiteXgauche)
            this.setDirection(2);
        if (this.getPositionX() >= limiteXdroit)
            this.setDirection(4);
        super.deplacer();
    }

    public Rectangle getHitbox() {
        return new Rectangle(getPositionX(), getPositionY(), 16, 16); // Taille de la sprite (16x16)
    }

}
