package universite_paris8.iut.mcontay.saejeu2d.vue;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;
import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.TilePane;

public class TerrainVue  {
    private Terrain Terrain;
    private TilePane panneauJeu;

    public TerrainVue(TilePane panneauJeu, Terrain terrain) {
        Terrain = terrain;
        this.panneauJeu = panneauJeu;
    }

    public void affichageVue () {
        Image terre1 = new Image(String.valueOf(Lanceur.class.getResource("terre1.png")));
        Image water1 = new Image(String.valueOf(Lanceur.class.getResource("water1.gif")));
        Image water2 = new Image(String.valueOf(Lanceur.class.getResource("water2.gif")));
        Image water3 = new Image(String.valueOf(Lanceur.class.getResource("water3.gif")));
        Image pierre1 = new Image(String.valueOf(Lanceur.class.getResource("pierre1.png")));
        Image pierre2 = new Image(String.valueOf(Lanceur.class.getResource("pierre2.png")));
        Image pierre3 = new Image(String.valueOf(Lanceur.class.getResource("pierre3.png")));
        Image pierre4 = new Image(String.valueOf(Lanceur.class.getResource("pierre4.png")));
        Image pierre5 = new Image(String.valueOf(Lanceur.class.getResource("pierre5.png")));
        Image pierre6 = new Image(String.valueOf(Lanceur.class.getResource("pierre6.png")));
        Image pierre7 = new Image(String.valueOf(Lanceur.class.getResource("pierre7.png")));



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
                        ImageView imgwater1 = new ImageView(water1);
                        panneauJeu.getChildren().add(imgwater1);
                        break;
                    case 4:
                        ImageView imgwater2 = new ImageView(water2);
                        panneauJeu.getChildren().add(imgwater2);
                        break;
                    case 5:
                        ImageView imgwater3 = new ImageView(water3);
                        panneauJeu.getChildren().add(imgwater3);
                        break;
                    case 6:
                        ImageView imgpierre2 = new ImageView(pierre2);
                        panneauJeu.getChildren().add(imgpierre2);
                        break;
                    case 7:
                        ImageView imgpierre3 = new ImageView(pierre3);
                        panneauJeu.getChildren().add(imgpierre3);
                        break;
                    case 8:
                        ImageView imgpierre4 = new ImageView(pierre4);
                        panneauJeu.getChildren().add(imgpierre4);
                        break;
                    case 9:
                        ImageView imgpierre5 = new ImageView(pierre5);
                        panneauJeu.getChildren().add(imgpierre5);
                        break;
                    case 10:
                        ImageView imgpierre6 = new ImageView(pierre6);
                        panneauJeu.getChildren().add(imgpierre6);
                        break;
                    case 11:
                        ImageView imgpierre7 = new ImageView(pierre7);
                        panneauJeu.getChildren().add(imgpierre7);
                        break;
                }
            }
        }
    }

}