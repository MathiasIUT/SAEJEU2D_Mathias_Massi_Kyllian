package universite_paris8.iut.mcontay.saejeu2d.controleur;

import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.modele.*;
import universite_paris8.iut.mcontay.saejeu2d.vue.CombatVue;
import universite_paris8.iut.mcontay.saejeu2d.vue.InventaireVue;
import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;
import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.input.KeyCode;
import javafx.scene.layout.TilePane;

import java.net.URL;
import java.util.ResourceBundle;
import java.util.HashSet;
import java.util.Set;

public class Controleur implements Initializable {
    @FXML
    private Pane pane;
    @FXML
    private TilePane tilePane;
    @FXML
    private Pane ptsDeVie;


    private Inventaire inventaire;
    private InventaireVue vue1;


    private Terrain terrain;
    private TerrainVue vue;


    private Joueur joueur;
    private JoueurVue joueurVue;
    private Personnage personnage ;

    private Combat combat;
    private CombatVue combatVue;


    private Set<KeyCode> keysPressed = new HashSet<>();

    private Vie barreDeVie;
    private GameLoop gameLoop;

    public Joueur getJoueur() {
        return this.joueur;
    }
    public Personnage getPersonnage() {
        return  this.personnage ;
    }

    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        terrain = new Terrain();
        joueur = new Joueur(terrain, "Joueur1",1, 256,  256,65);
        System.out.println(joueur.getVie());
        personnage = new Personnage(terrain,"pnj1", "100", "33",1, 100, 100);
        joueurVue = new JoueurVue(pane, joueur);
        this.vue = new TerrainVue(tilePane, terrain);
        this.vue.affichageVue();
        this.gameLoop = new GameLoop(joueur);
        gameLoop.initGameLoop();
        inventaire = new Inventaire();
        this.vue1 = new InventaireVue(pane,inventaire);
        this.vue1.afficherInventaire();
        combat = new Combat(joueur,inventaire);
        this.combatVue= new CombatVue(joueur,inventaire);
        barreDeVie = new Vie(pane, joueur);
    }

    public void mouvement(KeyEvent e) {
        KeyCode keyCode = e.getCode();
        if (e.getEventType() == KeyEvent.KEY_PRESSED) {
            keyPressed(keyCode);
        } else if (e.getEventType() == KeyEvent.KEY_RELEASED) {
            keyReleased(keyCode);
        }
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
