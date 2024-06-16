package universite_paris8.iut.mcontay.saejeu2d.modele;

public class Epee extends Objet {

    public Epee(String nom, String description, int degats, String imagePath, double x, double y) {
        super(nom, description, null, degats, imagePath, x, y);
    }

    public int getDegats() {
        return super.getDegats();
    }
}
