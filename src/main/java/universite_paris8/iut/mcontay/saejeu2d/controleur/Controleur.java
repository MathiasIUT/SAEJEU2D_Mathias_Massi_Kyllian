package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
import javafx.scene.input.MouseButton;
import javafx.scene.input.MouseEvent;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.modele.*;
import universite_paris8.iut.mcontay.saejeu2d.vue.*;
import java.io.IOException;
import java.net.URL;
import java.util.HashSet;
import java.util.ResourceBundle;
import java.util.Set;

public class Controleur implements Initializable {

    @FXML
    private Pane pane;

    private Environnement environnement;
    private Inventaire inventaire;
    private InventaireVue inventaireVue;
    private TerrainVue vue;
    private Joueur joueur;
    private JoueurVue joueurVue;
    private Monstre monstre;
    private MonstreVue monstreVue;
    private Skater skater;
    private SkaterVue skaterVue;

    private Bouclier bouclier;
    private BouclierVue bouclierVue;

    private Epee epee;
    private EpeeVue epeeVue;

    private VieVue vieVue;

    private Set<KeyCode> keysPressed = new HashSet<>();
    private GameLoop gameLoop;

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        environnement = new Environnement();
        try {
            vue = new TerrainVue(pane, environnement.getTerrain());
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
        vue.affichageVue();

        joueur = environnement.getJoueur();
        monstre = environnement.getMonstre();
        skater = environnement.getSkateur();

        epee = environnement.getEpee();
        bouclier = environnement.getBouclier();

        joueurVue = new JoueurVue(pane, environnement);
        monstreVue = new MonstreVue(pane, environnement.getMonstre());
        skaterVue = new SkaterVue(pane, skater);
        epeeVue = new EpeeVue(pane, epee);
        bouclierVue = new BouclierVue(pane, bouclier);

        inventaire = joueur.getInventaire();
        inventaireVue = new InventaireVue(pane, inventaire, joueur, epee, environnement);

        vieVue = new VieVue(pane, joueur);

        pane.setFocusTraversable(true);
        pane.requestFocus();

        pane.addEventFilter(MouseEvent.MOUSE_CLICKED, this::gererClicSouris);

        gameLoop = new GameLoop(environnement, this::update);
        gameLoop.initGameLoop();
    }

    public Joueur getJoueur() {
        return joueur;
    }

    public void setJoueurEtSkateur(Joueur joueur, Skater skater) {
        this.joueur = joueur;
        this.skater = skater;
    }

    public void mouvement(KeyEvent e) {
        KeyCode keyCode = e.getCode();
        if (e.getEventType() == KeyEvent.KEY_PRESSED) {
            keyPressed(keyCode);
        } else if (e.getEventType() == KeyEvent.KEY_RELEASED) {
            keyReleased(keyCode);
        }
    }

    public void keyPressed(KeyCode keyCode) {
        if (joueur.estMortProperty().get()) {
            return;
        }

        keysPressed.add(keyCode);
        if (keyCode == KeyCode.E) {
            for (Objet objet : environnement.getListeObjets()) {
                if (joueur.estProcheObjet(objet)) {
                    joueur.getInventaire().ajouterObjet(objet);

                    pane.getChildren().removeIf(node -> {
                        if (node instanceof ImageView) {
                            ImageView imageView = (ImageView) node;
                            return imageView.getImage() == objet.getImage();
                        }
                        return false;
                    });

                    environnement.getListeObjets().remove(objet);
                    System.out.println("Objet récupéré: " + objet.getNom());
                    break;
                }
            }
            inventaireVue.afficherInventaire();
        } else if (keyCode == KeyCode.H) {
            joueur.initierDialogue(skater, pane);
        }
    }

    public void keyReleased(KeyCode keyCode) {
        keysPressed.remove(keyCode);
        joueur.deplacementStop();
    }

    private void update() {
        boolean deplace = false;
        if (keysPressed.contains(KeyCode.Z)) {
            joueur.deplacementHaut();
            deplace = true;
        }
        if (keysPressed.contains(KeyCode.Q)) {
            joueur.deplacementGauche();
            deplace = true;
        }
        if (keysPressed.contains(KeyCode.S)) {
            joueur.deplacementBas();
            deplace = true;
        }
        if (keysPressed.contains(KeyCode.D)) {
            joueur.deplacementDroite();
            deplace = true;
        }
    }

    private void gererClicSouris(MouseEvent event) {
        if (event.getButton() == MouseButton.SECONDARY && !joueur.estMortProperty().get()) {
            joueurVue.VueAttaque(joueur.getDirection());
        }
    }
}
