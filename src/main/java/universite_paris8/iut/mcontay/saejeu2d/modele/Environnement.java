package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

/* La classe Environnement initialise et gère les composants du jeu, y compris les entités comme le joueur, le monstre et le skateur, ainsi que les objets comme l'épée et le bouclier.
 Elle maintient des listes observables d'acteurs et d'objets, et suit le nombre de tours de jeu avec une propriété.
  Le constructeur initialise le terrain et les entités, ajoutant celles-ci aux listes correspondantes. La méthode faireUnTour déplace chaque entité et met à jour le nombre de tours,
 tandis que d'autres méthodes permettent de gérer la suppression des entités et d'accéder aux composants spécifiques. */

public class Environnement {

    private IntegerProperty nbTours;
    private Terrain terrain;
    private Joueur joueur;
    private Monstre monstre;
    private Skateur skateur;
    private ObservableList<Entite> listeActeurs;
    private ObservableList<Objet> listeObjets;

    public Environnement() {
        nbTours = new SimpleIntegerProperty(0);
        listeActeurs = FXCollections.observableArrayList();
        listeObjets = FXCollections.observableArrayList();
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        joueur = new Joueur(this, terrain, "Hector", 100, 100, 100, 300, 400, 1, 3);
        monstre = new Monstre(this, terrain, "Vilain Monstre", 100, 25, 50, 655, 45, 2, 15);
        skateur = new Skateur(this, terrain, "Rat Skateur", 100, 300, 420, 3, 2);
        listeActeurs.addAll(joueur, monstre, skateur);

        Epee epee = new Epee("Épée", "Une épée tranchante", 20, "/universite_paris8/iut/mcontay/saejeu2d/Épée.png", 352, 332);
        Bouclier bouclier = new Bouclier("Bouclier", "Un bouclier robuste", terrain, 5, "/universite_paris8/iut/mcontay/saejeu2d/Bouclier.png", 1104, 350, 30);
        listeObjets.addAll(epee, bouclier);
    }

    public ObservableList<Entite> getListeActeurs() {
        return listeActeurs;
    }

    public ObservableList<Objet> getListeObjets() {
        return listeObjets;
    }

    public IntegerProperty nbToursProperty() {
        return nbTours;
    }

    public int getNbTours() {
        return nbTours.get();
    }

    public boolean faireUnTour() {
        for (Entite e : listeActeurs) {
            e.deplacer();
        }
        nbTours.set(nbTours.get() + 1);
        if (joueur.getPtsDeVie() <= 0) {
            joueur.setEstMort(true);
            estMort(joueur);
            return false;
        }
        return true;
    }

    public void supprimerEntiteParId(int id) {
        listeActeurs.removeIf(entite -> entite.getId() == id);
    }

    public void estMort(Entite entite) {
        listeActeurs.remove(entite);
    }

    public Joueur getJoueur() {
        return joueur;
    }

    public Monstre getMonstre() {
        return monstre;
    }

    public Terrain getTerrain() {
        return terrain;
    }

    public Skateur getSkateur() {
        return skateur;
    }

    public Epee getEpee() {
        return (Epee) listeObjets.filtered(obj -> obj instanceof Epee).get(0);
    }

    public Bouclier getBouclier() {
        return (Bouclier) listeObjets.filtered(obj -> obj instanceof Bouclier).get(0);
    }
}
