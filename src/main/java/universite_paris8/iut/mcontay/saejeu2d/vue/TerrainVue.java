package universite_paris8.iut.mcontay.saejeu2d.vue;

import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.TilePane;

public class TerrainVue extends Terrain {
    private Terrain Terrain;
    private TilePane panneauJeu;

    public TerrainVue(int longueur, int hauteur, TilePane panneauJeu, Terrain terrain) {
        super(longueur, hauteur);
        Terrain = terrain;
        this.panneauJeu = panneauJeu;
    }

    public void affichageVue () {
    Image terre1 = new Image(String.valueOf(Lanceur.class.getResource("terre1.png")));
        Image eau1 = new Image(String.valueOf(Lanceur.class.getResource("eau1.png")));
        Image pierre1 = new Image(String.valueOf(Lanceur.class.getResource("pierre1.png")));

        for (int i = 0; i < Terrain.getCodesTuiles().length; i++) {
            for (int j = 0; j < Terrain.getCodesTuiles()[i].length; j++) {
                switch (Terrain.getCodesTuiles()[i][j]) {
                    case 1:
                        ImageView imgpierre1 = new ImageView(pierre1);
                        panneauJeu.getChildren().add(imgpierre1);
                        break;
                    case 2:
                        ImageView imgterre1 = new ImageView(terre1);
                        panneauJeu.getChildren().add(imgterre1);
                        break;
                    case 3:
                        ImageView imgeau1 = new ImageView(eau1);
                        panneauJeu.getChildren().add(imgeau1);
                        break;
                }
            }
        }
    }

}
