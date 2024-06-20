package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleIntegerProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

public class Environnement {

    private IntegerProperty nbTours;
    private Terrain terrain;
    private Joueur joueur;
    private Monstre monstre;
    private Skater skater;
    private ObservableList<Entite> listeActeurs;
    private ObservableList<Objet> listeObjets;

    public Environnement() {
        nbTours = new SimpleIntegerProperty(0);
        listeActeurs = FXCollections.observableArrayList();
        listeObjets = FXCollections.observableArrayList();
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        joueur = new Joueur(this, terrain, "Hector", 100, 100, 100, 300, 400, 1, 3);
        monstre = new Monstre(this, terrain, "Vilain Monstre", 100, 25, 50, 655, 45, 2, 15);
        skater = new Skater(this, terrain, "Rat Skateur", 100, 300, 420, 3, 2);
        listeActeurs.addAll(joueur, monstre, skater);

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

    // Correct return types for these methods
    public Joueur getJoueur() {
        return joueur;
    }

    public Monstre getMonstre() {
        return monstre;
    }

    public Terrain getTerrain() {
        return terrain;
    }

    public Skater getSkateur() {
        return skater;
    }

    public Epee getEpee() {
        return (Epee) listeObjets.filtered(obj -> obj instanceof Epee).get(0);
    }

    public Bouclier getBouclier() {
        return (Bouclier) listeObjets.filtered(obj -> obj instanceof Bouclier).get(0);
    }

    public void ajouterEntite(Entite entite) {
        listeActeurs.add(entite);
    }
}
