package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import universite_paris8.iut.mcontay.saejeu2d.Lanceur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

import java.io.IOException;
import java.net.URL;
import java.util.HashMap;
import java.util.Map;

public class TerrainVue {
    private static final int TILE_SIZE = 16; // Tile size is 16x16 pixels
    private Pane paneJeu;
    private Terrain terrain;
    private Map<Integer, Image> tileImages;

    public TerrainVue(Pane paneJeu, Terrain terrain) throws IOException {
        this.paneJeu = paneJeu;
        this.terrain = terrain;
        loadTileImages();
    }

    private void loadTileImages() throws IOException {
        tileImages = new HashMap<>();
        // code tuiles de 0 à n, remplacer n avec le nombre de tiles si on change on en a 419 actuellement
        for (int i = 0; i <= 419; i++) {
            URL imageUrl = Lanceur.class.getResource("/universite_paris8/iut/mcontay/saejeu2d/map/tile_" + i + ".png");
            tileImages.put(i+1, new Image(imageUrl.openStream()));
        }
    }

    public void affichageVue() {
        if (terrain.getSolLayer() != null) {
            renderLayer(terrain.getSolLayer());
        } else {
            System.err.println("Sol layer is null.");
        }

        if (terrain.getDecoLayer() != null) {
            renderLayer(terrain.getDecoLayer());
        } else {
            System.err.println("Deco layer is null.");
        }
    }

    private void renderLayer(int[][] layer) {
        for (int i = 0; i < layer.length; i++) {
            for (int j = 0; j < layer[i].length; j++) {
                renderTile(layer[i][j], j, i);
            }
        }
    }

    private void renderTile(int tileCode, int x, int y) {

        Image tileImage = tileImages.get(tileCode);
        if (tileImage != null) {
            ImageView imageView = new ImageView(tileImage);
            imageView.setX(x * TILE_SIZE);
            imageView.setY(y * TILE_SIZE);
            paneJeu.getChildren().add(imageView);
        }
    }
}
