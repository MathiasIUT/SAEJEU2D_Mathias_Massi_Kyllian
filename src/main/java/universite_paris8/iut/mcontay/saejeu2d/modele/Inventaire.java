package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.DoubleProperty;
import javafx.beans.property.SimpleDoubleProperty;
import javafx.collections.FXCollections;
import javafx.collections.ObservableList;

public class Inventaire {

    private static ObservableList<Objet> objets;

    public Inventaire() {
        this.objets = FXCollections.observableArrayList();
    }


    public static ObservableList<Objet> getObjets() {
        return objets;
    }
    
    public static void ajouterObjet(Objet objet) {
        objets.add(objet);
    }

    public void retirerObjet(Objet objet) {
        objets.remove(objet);
    }

  public static boolean contientObjet(Objet objet) {
      return objets.contains(objet);
    }

    @Override
    public String toString() {
        StringBuilder sb = new StringBuilder("Inventaire:\n");
        for (Objet objet : objets) {
            sb.append(objet.toString()).append("\n");
        }
        return sb.toString();
    }
}
