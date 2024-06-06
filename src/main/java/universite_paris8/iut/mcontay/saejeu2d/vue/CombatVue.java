package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.control.ChoiceDialog;
import javafx.scene.control.Alert;
import javafx.scene.control.Alert.AlertType;
import universite_paris8.iut.mcontay.saejeu2d.modele.Combat;
import universite_paris8.iut.mcontay.saejeu2d.modele.Inventaire;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Objet;

import java.util.List;
import java.util.Optional;
import java.util.stream.Collectors;

public class CombatVue {
    private Combat combat;

    public CombatVue(Joueur joueur, Inventaire inventaire) {
        this.combat = new Combat(joueur, inventaire);
    }

    public void demanderArmeEtAttaquer() {
        List<Objet> armes = combat.getInventaire().getObjets();
        List<String> nomsArmes = armes.stream().map(Objet::getNom).collect(Collectors.toList());

        ChoiceDialog<String> dialog = new ChoiceDialog<>(nomsArmes.get(0), nomsArmes);
        dialog.setTitle("Choisir une arme");
        dialog.setHeaderText("Sélectionnez une arme pour attaquer");
        dialog.setContentText("Arme :");

        Optional<String> result = dialog.showAndWait();
        if (result.isPresent()) {
            String armeChoisie = result.get();
            Objet arme = armes.stream().filter(objet -> objet.getNom().equals(armeChoisie)).findFirst().orElse(null);
            if (arme != null) {
                combat.attaquer(arme);
                afficherVieRestante(combat.getJoueur().getVie());
            }
        }
    }

    private void afficherVieRestante(int vie) {
        Alert alert = new Alert(AlertType.INFORMATION);
        alert.setTitle("Vie du joueur");
        alert.setHeaderText(null);
        alert.setContentText("Vie restante du joueur : " + vie);
        alert.showAndWait();
    }
}




