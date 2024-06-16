package universite_paris8.iut.mcontay.saejeu2d.modele;

/* La classe Epee hérite de Objet et représente une épée avec des dégâts spécifiques, initialisée avec son nom, description, chemin d'image et position.
 Elle utilise la méthode getDegats de la classe parente pour retourner les dégâts de l'épée. */
public class Epee extends Objet {

    public Epee(String nom, String description, int degats, String imagePath, double x, double y) {
        super(nom, description, null, degats, imagePath, x, y);
    }

    public int getDegats() {
        return super.getDegats();
    }
}
