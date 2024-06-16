package universite_paris8.iut.mcontay.saejeu2d;

import javafx.application.Application;
import javafx.fxml.FXMLLoader;
import javafx.scene.Scene;
import javafx.scene.layout.BorderPane;
import javafx.stage.Stage;
import universite_paris8.iut.mcontay.saejeu2d.controleur.Controleur;
import java.io.IOException;

/*La classe Lanceur initialise et affiche l'interface principale de l'application JavaFX
 en chargeant un fichier FXML, en définissant une scène et en ajoutant une icône à la fenêtre de l'application.*/

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
        stage.setTitle("Paris Adventure");

        // Section pour charger l'icône en utilisant getResourceAsStream
//        try (InputStream iconStream = getClass().getResourceAsStream("/universite_paris8/iut/mcontay/saejeu2d/iconjeu.webp")) {
//            if (iconStream != null) {
//                stage.getIcons().add(new Image(iconStream));
//            } else {
//                System.out.println("L'icône du jeu n'a pas pu être chargée.");
//            }
//        } catch (IOException e) {
//            e.printStackTrace();
//        }

        stage.setScene(scene);
        stage.show();
    }

    public static void main(String[] args) {
        launch();
    }
}
