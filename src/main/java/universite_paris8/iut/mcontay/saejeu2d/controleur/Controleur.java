package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.input.KeyCode;
import javafx.scene.input.KeyEvent;
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
    private Pane paneJeu;

    private Terrain terrain;
    private TerrainVue terrainVue;
    @FXML
    private Pane ptsDeVie;

    private Inventaire inventaire;
    private InventaireVue vue1;

    private TerrainVue vue;

    private Joueur joueur;
    private JoueurVue joueurVue;
    private Personnage personnage;
    private Combat combat;
    private CombatVue combatVue;

    private Set<KeyCode> keysPressed = new HashSet<>();

    private Vie barreDeVie;
    private VieVue vieVue;
    private GameLoop gameLoop;

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        try {
            terrainVue = new TerrainVue(paneJeu, terrain);
        } catch (IOException e) {
            throw new RuntimeException(e);
        }
        terrainVue.affichageVue();
        joueur = new Joueur(terrain, "Joueur1", 1, 400, 450, 100);
        System.out.println(joueur.getVie());
        personnage = new Personnage(terrain, "pnj1", "100", "33", 1, 100, 100);
        joueurVue = new JoueurVue(paneJeu, joueur);

        this.gameLoop = new GameLoop(joueur);
        gameLoop.initGameLoop();
        inventaire = new Inventaire();
        this.vue1 = new InventaireVue(paneJeu, inventaire, joueur);
        this.vue1.afficherInventaire();
        combat = new Combat(joueur, inventaire);
        this.combatVue = new CombatVue(joueur, inventaire);
        barreDeVie = new Vie(paneJeu, joueur);
        this.vieVue = new VieVue(paneJeu, joueur);
    }

    public void mouvement(KeyEvent e) {
        KeyCode keyCode = e.getCode();
        if (e.getEventType() == KeyEvent.KEY_PRESSED) {
            keyPressed(keyCode);
        } else if (e.getEventType() == KeyEvent.KEY_RELEASED) {
            keyReleased(keyCode);
        }
    }

    public Joueur getJoueur() {
        return this.joueur;
    }

    public Personnage getPersonnage() {
        return this.personnage;
    }

    private void update() {
        if (keysPressed.contains(KeyCode.Z)) {
            joueur.deplacementHaut();
            joueurVue.changerImage(KeyCode.Z);
        }
        if (keysPressed.contains(KeyCode.Q)) {
            joueur.deplacementGauche();
            joueurVue.changerImage(KeyCode.Q);
        }
        if (keysPressed.contains(KeyCode.S)) {
            joueur.deplacementBas();
            joueurVue.changerImage(KeyCode.S);
        }
        if (keysPressed.contains(KeyCode.D)) {
            joueur.deplacementDroite();
            joueurVue.changerImage(KeyCode.D);
        }
    }

    public void keyPressed(KeyCode keyCode) {
        keysPressed.add(keyCode);
        update();
        if (keyCode == KeyCode.I) {
            combatVue.demanderArmeEtAttaquer();
        }
    }

    public void keyReleased(KeyCode keyCode) {
        keysPressed.remove(keyCode);
        update();
        joueur.deplacementStop();
    }
}
