package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

public class Inventaire {

    private ObservableList<Objet> listeObjets;
    private Epee epeeEquipee;

    public Inventaire() {
        this.listeObjets = FXCollections.observableArrayList();
    }

    public ObservableList<Objet> getObjets() {
        return listeObjets;
    }

    public void ajouterObjet(Objet objet) {
        listeObjets.add(objet);
    }

    public void retirerObjet(Objet objet) {
        listeObjets.remove(objet);
    }

    public boolean contientObjet(Objet objet) {
        return listeObjets.contains(objet);
    }

    public Objet getObjetParNom(String nom) {
        return listeObjets.stream().filter(objet -> objet.getNom().equals(nom)).findFirst().orElse(null);
    }

    public void equiperArme(Epee epee) {
        if (contientObjet(epee)) {
            this.epeeEquipee = epee;
            System.out.println("Arme équipée: " + epee.getNom());
        } else {
            System.out.println("L'arme sélectionnée n'est pas dans l'inventaire.");
        }
    }

    public void desequiperArme() {
        if (this.epeeEquipee != null) {
            System.out.println("Arme déséquipée: " + this.epeeEquipee.getNom());
            this.epeeEquipee = null;
        }
    }

    public Epee getArmeEquipee() {
        return epeeEquipee;
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder("Inventaire:\n");
        for (Objet objet : listeObjets) {
            sb.append(objet.toString()).append("\n");
        }
        return sb.toString();
    }
}
