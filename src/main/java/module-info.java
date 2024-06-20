module SAEJEU2D {
    requires javafx.controls;
    requires javafx.fxml;
    requires java.desktop;
    requires com.fasterxml.jackson.annotation;
    requires com.fasterxml.jackson.databind;
    requires com.google.gson;
    requires org.testng;
//    requires org.junit.jupiter.api;

    opens universite_paris8.iut.mcontay.saejeu2d to javafx.fxml;
    exports universite_paris8.iut.mcontay.saejeu2d;
    exports universite_paris8.iut.mcontay.saejeu2d.controleur;
    opens universite_paris8.iut.mcontay.saejeu2d.controleur to javafx.fxml;
    exports universite_paris8.iut.mcontay.saejeu2d.modele;
    opens universite_paris8.iut.mcontay.saejeu2d.modele to javafx.fxml;
    exports universite_paris8.iut.mcontay.saejeu2d.vue;
    opens universite_paris8.iut.mcontay.saejeu2d.vue to javafx.fxml;
}
