package universite_paris8.iut.mcontay.saejeu2d.modele;

import javafx.beans.binding.Bindings;
import javafx.beans.binding.DoubleBinding;
import javafx.scene.layout.Pane;


public class GameCamera {

    private final DoubleBinding offsetX;
    private final DoubleBinding offsetY;
    private final Pane pane;

    public GameCamera(Pane pane, Joueur joueur, double sceneWidth, double sceneHeight) {
        this.pane = pane;

        // Check si les propriétés de terrain fonctionnent
        if (joueur.getEnvironnement().getTerrain().widthProperty() == null ||
                joueur.getEnvironnement().getTerrain().heightProperty() == null) {
            throw new IllegalArgumentException("Terrain propriétés devrait pas être nulles");
        }


        this.offsetX = Bindings.createDoubleBinding(() ->
                        Math.min(0, Math.max(-((16*80) - sceneWidth),
                                sceneWidth / 2 - joueur.getPositionX())),
                joueur.positionXProperty(), joueur.getEnvironnement().getTerrain().widthProperty());

        this.offsetY = Bindings.createDoubleBinding(() ->
                        Math.min(0, Math.max(-((16*60) - sceneHeight),
                                sceneHeight / 2 - joueur.getPositionY())),
                joueur.positionYProperty(), joueur.getEnvironnement().getTerrain().heightProperty());


        pane.translateXProperty().bind(offsetX);
        pane.translateYProperty().bind(offsetY);
    }

    public void gameUpdate() {
        pane.setTranslateX(offsetX.get());
        pane.setTranslateY(offsetY.get());
    }
}
