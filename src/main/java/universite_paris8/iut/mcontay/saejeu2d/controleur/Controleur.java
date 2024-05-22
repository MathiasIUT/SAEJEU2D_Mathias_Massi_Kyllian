package universite_paris8.iut.mcontay.saejeu2d.controleur;


import javafx.animation.KeyFrame;
import javafx.animation.Timeline;
import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
import javafx.scene.layout.StackPane;
import javafx.util.Duration;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.GameLoop;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.input.KeyEvent;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.vue.JoueurVue;

import universite_paris8.iut.mcontay.saejeu2d.vue.TerrainVue;
import javafx.fxml.FXML;
import javafx.fxml.Initializable;
import javafx.scene.layout.TilePane;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.io.IOException;
import java.net.URL;
import java.util.ResourceBundle;

import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import javafx.scene.input.KeyCode;

public class Controleur implements Initializable {

    @FXML
    private universite_paris8.iut.mcontay.saejeu2d.modele.Terrain terrain;
    @FXML
    private Pane pane;
    @FXML
    private TilePane TilePane;
    private TerrainVue vue;
    private Joueur joueur;

    private JoueurVue joueurVue;

    private Timeline gameLoop;

    private int temps;


    public Joueur getJoueur(){
        return this.joueur ;
    }
    @Override
    public void initialize(URL url, ResourceBundle resourceBundle) {
        terrain = new Terrain();
        joueur = new Joueur(terrain, 0, 0);
        joueurVue = new JoueurVue(pane,joueur);
        this.vue = new TerrainVue(TilePane, terrain);
        this.vue.affichageVue();
        GameLoop gameloop = new GameLoop();
        gameloop.initAnimation();


    }

    public void mouvement (KeyEvent e){

        KeyCode keyCode = e.getCode();
        double x ;
        double y ;
        x = joueur.getPositionX();
        y = joueur.getPositionY();
        joueurVue.VuePositionJoueur(x, y, keyCode);
        if (e.getCode() == KeyCode.Z) {
            joueur.seDeplaceHaut();
        } else if (e.getCode() == KeyCode.Q) {
            joueur.seDeplaceGauche();
        } else if (e.getCode() == KeyCode.S) {
            joueur.seDeplaceBas();
        } else if (e.getCode() == KeyCode.D) {
            joueur.seDeplaceDroite();
        }
    }






}


