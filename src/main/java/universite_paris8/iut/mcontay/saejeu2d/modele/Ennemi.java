package universite_paris8.iut.mcontay.saejeu2d.modele;

public class Ennemi extends Entite {
    public Ennemi(Terrain terrain, String nom, int id, double initialX, double initialY) {
        super(terrain, nom, id, initialX, initialY);
    }

    public void deplacer(){
        // changer de direction
        super.deplacer();

    }

}
