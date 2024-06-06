package universite_paris8.iut.mcontay.saejeu2d.vue;

import javafx.scene.image.Image;
import javafx.scene.image.ImageView;
import javafx.scene.layout.Pane;
import javafx.scene.paint.Color;
import javafx.scene.shape.Rectangle;
import javafx.scene.text.Font;
import javafx.scene.text.Text;
import universite_paris8.iut.mcontay.saejeu2d.modele.Joueur;
import universite_paris8.iut.mcontay.saejeu2d.modele.Terrain;

public class VieVue {
    private Pane pane;
    private Joueur joueur;
    private Terrain terrain;
    private Image[] imagesVie;
    private ImageView vieView;
    private Rectangle fond;
    private Rectangle barre;
    public VieVue(Pane pane, Joueur joueur) {
        this.joueur = joueur;
        this.terrain = terrain;

        fond = new Rectangle(100, 10);
        fond.setFill(Color.RED);

        barre = new Rectangle(100, 10);
        barre.setFill(Color.GREEN);

        pane.getChildren().addAll(fond, barre);

        joueur.vieProperty().addListener((observable, oldValue, newValue) -> {
            double pourcentage = (double) newValue.intValue() / joueur.getVieMax();
            barre.setWidth(pourcentage * 100);
        });

    }
    public void afficherEcranMort() {
        Rectangle ecranNoir = new Rectangle(pane.getWidth(), pane.getHeight(), Color.BLACK);
        ecranNoir.setOpacity(0.7);
        ecranNoir.widthProperty().bind(pane.widthProperty());
        ecranNoir.heightProperty().bind(pane.heightProperty());
        pane.getChildren().add(ecranNoir);
        Text texteMort = new Text("You are Dead");
        texteMort.setFill(Color.RED);
        texteMort.setFont(Font.font("Verdana", 50));
        texteMort.xProperty().bind(pane.widthProperty().subtract(texteMort.getLayoutBounds().getWidth()).divide(3));
        texteMort.yProperty().bind(pane.heightProperty().subtract(texteMort.getLayoutBounds().getHeight()).divide(3).add(texteMort.getFont().getSize()));
        pane.getChildren().add(texteMort);
    }
}
