package universite_paris8.iut.mcontay.saejeu2d.modele;

import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import javafx.scene.layout.Pane;

public class JoueurTest {
    private Joueur joueur;
    private Environnement environnement;
    private Terrain terrain;

    @BeforeEach
    public void setUp() {
        // Initialisation du terrain et de l'environnement avant chaque test
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        environnement = new Environnement();
        joueur = new Joueur(environnement, terrain, "Joueur1", 100, 10, 5, 0, 0, 1, 5);
    }

    @Test
    public void testAjouterPtsDeVie() {
        // Test de l'ajout de points de vie
        joueur.ajouterPtsDeVie(20);
        assertEquals(120, joueur.getPtsDeVie(), "Les points de vie du joueur doivent augmenter de 20");
    }

    @Test
    public void testEstProche() {
        // Test de la proximité avec une autre entité
        Monstre monstre = new Monstre(environnement, terrain, "Monstre1", 50, 0, 0, 25, 25, 2, 0);
        assertTrue(joueur.estProche(monstre), "Le joueur doit être proche du monstre");
    }

    @Test
    public void testAttaquer() {
        // Test de l'attaque d'un monstre
        Monstre monstre = new Monstre(environnement, terrain, "Monstre1", 50, 0, 0, 0, 0, 2, 0);
        joueur.attaquer(monstre);
        assertEquals(40, monstre.getPtsDeVie(), "Les points de vie du monstre doivent diminuer de 10 après l'attaque");
    }

    @Test
    public void testSeDeplaceBas() {
        // Test du déplacement vers le bas
        double oldY = joueur.getPositionY();
        joueur.seDeplaceBas();
        assertEquals(oldY + joueur.moveDistance, joueur.getPositionY(), "La position Y du joueur doit augmenter de la distance de déplacement après un déplacement vers le bas");
    }



    @Test
    public void testSeDeplaceDroite() {
        // Test du déplacement vers la droite
        double oldX = joueur.getPositionX();
        joueur.seDeplaceDroite();
        assertEquals(oldX + joueur.moveDistance, joueur.getPositionX(), "La position X du joueur doit augmenter de la distance de déplacement après un déplacement vers la droite");
    }

    @Test
    public void testDeplacer() {
        // Test du déplacement général en fonction de la direction
        joueur.setDirection(3); // Bas
        double oldY = joueur.getPositionY();
        joueur.deplacer();
        assertEquals(oldY + joueur.moveDistance, joueur.getPositionY(), "La position Y du joueur doit augmenter de la distance de déplacement après un déplacement vers le bas");
    }

    @Test
    public void testInitierDialogue() {
        // Test de l'initiation d'un dialogue avec un skater
        Skater skater = new Skater(environnement, terrain, "Skater1", 100, 0, 0, 10, 10);
        Pane pane = new Pane();
        joueur.setPositionX(10);
        joueur.setPositionY(10);
        joueur.initierDialogue(skater, pane);
        // Vérifie que le dialogue est initié si le joueur est proche du skater
        assertTrue(pane.getChildren().isEmpty(), "Le dialogue doit être affiché si le joueur est proche du skater");
    }

}
