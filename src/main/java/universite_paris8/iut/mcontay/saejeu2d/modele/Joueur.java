package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.property.BooleanProperty;
import javafx.beans.property.IntegerProperty;
import javafx.beans.property.SimpleBooleanProperty;
import javafx.beans.property.SimpleIntegerProperty;
import javafx.scene.layout.Pane;
import javafx.scene.layout.VBox;
import javafx.scene.control.Label;
import javafx.scene.control.Button;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Font;

/* La classe Joueur représente un joueur avec des points de vie et des points de vie supplémentaires, un inventaire et une propriété indiquant s'il est mort.
 Elle peut attaquer des monstres proches, ajouter des points de vie, et vérifier la proximité d'entités ou d'objets.
  La méthode deplacer gère les collisions avec les pnj, tandis que initierDialogue et afficherDialogue gèrent l'affichage de dialogues interactifs avec le skateur.
 Les propriétés JavaFX permettent de lier et d'observer les changements d'état du joueur, comme les points de vie et la mort. */


public class Joueur extends PersoQuiAttaque {

    private IntegerProperty ptsDeVie;
    private IntegerProperty vieSupplementaire;
    private final int maxPtsDeVie;
    private Inventaire inventaire;
    private BooleanProperty estMort;

    public Joueur(Environnement environnement, Terrain terrain, String nom, int vie, int ptsAttaque, int ptsDefense, double initialX, double initialY, int id, int moveDistance) {
        super(environnement, terrain, nom, vie, ptsAttaque, ptsDefense, initialX, initialY, id, moveDistance);
        this.ptsDeVie = new SimpleIntegerProperty(vie);
        this.vieSupplementaire = new SimpleIntegerProperty(0);
        this.inventaire = new Inventaire();
        this.maxPtsDeVie = vie;
        this.estMort = new SimpleBooleanProperty(false);
        this.ptsDeVie.addListener((observable, oldValue, newValue) -> {
            if (newValue.intValue() <= 0) {
                setEstMort(true);
            }
        });
    }

    public int getMaxPtsDeVie() {
        return maxPtsDeVie;
    }

    public void attaquer(Monstre monstre) {
        if (estProche(monstre)) {
            monstre.setPtsDeVie(monstre.getPtsDeVie() - 10);
            System.out.println("Le monstre a été attaqué ! Il lui reste " + monstre.getPtsDeVie() + " points de vie.");
            if (monstre.getPtsDeVie() <= 0) {
                monstre.setEstMort(true);
                getEnvironnement().supprimerEntiteParId(monstre.getId());
            }
        }
    }

    public void ajouterPtsDeVie(int vie) {
        this.setPtsDeVie(this.getPtsDeVie() + vie);
    }

    public boolean estProche(Entite entite) {
        if (entite == null) {
            return false;
        }
        double distance = Math.sqrt(Math.pow(this.getPositionX() - entite.getPositionX(), 2) + Math.pow(this.getPositionY() - entite.getPositionY(), 2));
        return distance < 50; // par exemple, une distance de 50 pixels
    }

    public boolean estProcheObjet(Objet objet) {
        double distance = Math.sqrt(Math.pow(this.getPositionX() - objet.getPositionX(), 2) + Math.pow(this.getPositionY() - objet.getPositionY(), 2));
        return distance < 50; // par exemple, une distance de 50 pixels
    }

    public void setPtsDeVie(int ptsDeVie) {
        this.ptsDeVie.set(Math.max(ptsDeVie, 0));
    }

    public IntegerProperty ptsDeVieProperty() {
        return ptsDeVie;
    }

    public int getPtsDeVie() {
        return ptsDeVie.get();
    }

    public void setVieSupplementaire(int vieSupplementaire) {
        this.vieSupplementaire.set(vieSupplementaire);
    }

    public IntegerProperty vieSupplementaireProperty() {
        return vieSupplementaire;
    }

    public int getVieSupplementaire() {
        return vieSupplementaire.get();
    }

    public BooleanProperty estMortProperty() {
        return estMort;
    }

    public void setEstMort(boolean estMort) {
        this.estMort.set(estMort);
    }

    public Inventaire getInventaire() {
        return inventaire;
    }

    public void ajouterVieSupplementaire(int vie) {
        this.vieSupplementaire.set(this.vieSupplementaire.get() + vie);
        this.setPtsDeVie(this.getPtsDeVie() + vie);
    }

    @Override
    public void deplacer() {
        double oldX = getPositionX();
        double oldY = getPositionY();
        super.deplacer();

        for (Entite entite : getEnvironnement().getListeActeurs()) {
            if (entite instanceof Skateur) {
                Rectangle hitbox = ((Skateur) entite).getHitbox();
                if (hitbox.intersects(getPositionX(), getPositionY(), 16, 16)) {
                    setPositionX(oldX);
                    setPositionY(oldY);
                    break;
                }
            }
        }
    }

    public void initierDialogue(Skateur skateur, Pane pane) {
        if (estProche(skateur)) {
            afficherDialogue(skateur, pane);
        }
    }

    private void afficherDialogue(Skateur skateur, Pane pane) {
        VBox dialogueBox = new VBox();
        dialogueBox.setStyle("-fx-background-color: rgba(0, 0, 0, 0.8); -fx-padding: 10; -fx-border-color: white; -fx-border-width: 2;");
        dialogueBox.setLayoutX(100);
        dialogueBox.setLayoutY(100);

        Label dialogueLabel = new Label("Bonjour, je suis le rat skateur ! \n Je suppose que tu viens me voir pour en savoir plus sur les égouts, si tu veux tout savoir,\n les égouts sont au nord de la ville, tu trouveras la bas le scientifique fou.\n Je te demande seulement de faire attention car il y a un Vilain Monstre qui rôde là bas, bon courage à toi !");
        dialogueLabel.setTextFill(Color.WHITE);
        dialogueLabel.setFont(Font.font("Verdana", 14));

        Button closeButton = new Button("Fermer");
        closeButton.setOnAction(event -> pane.getChildren().remove(dialogueBox));

        dialogueBox.getChildren().addAll(dialogueLabel, closeButton);

        pane.getChildren().add(dialogueBox);
    }
}
