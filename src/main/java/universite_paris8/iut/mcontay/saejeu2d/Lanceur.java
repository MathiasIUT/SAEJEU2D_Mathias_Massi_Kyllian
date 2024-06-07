package universite_paris8.iut.mcontay.saejeu2d;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;
import universite_paris8.iut.mcontay.saejeu2d.controleur.Controleur;

import java.io.IOException;

public class Lanceur extends Application {
    @Override
    public void start(Stage stage) throws IOException {
        FXMLLoader fxmlLoader = new FXMLLoader(getClass().getResource("/universite_paris8/iut/mcontay/saejeu2d/InterfacePrincipale.fxml"));
        BorderPane panePrincipal = fxmlLoader.load();

        Controleur controleur = fxmlLoader.getController();
        System.out.println(controleur);

        Scene scene = new Scene(panePrincipal, 1280, 960);

        scene.setOnKeyPressed(controleur::mouvement);
        scene.setOnKeyReleased(controleur::mouvement);
        stage.setTitle("Jeu 2D");
        stage.setScene(scene);
        stage.show();
    }

    public static void main(String[] args) {
        launch();
    }
}
