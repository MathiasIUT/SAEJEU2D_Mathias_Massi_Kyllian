package universite_paris8.iut.mcontay.saejeu2d.modele;

import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;
import javafx.scene.shape.Rectangle;

public class JoueurTest {
    private Joueur joueur;
    private BFS bfs;
    private Environnement environnement;
    private Terrain terrain;

    @BeforeEach
    public void setUp() {
        // Initialisez le terrain avec une valeur correcte
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        bfs = new BFS(terrain); // Passez le terrain à BFS
        environnement = new Environnement();
        joueur = new Joueur(environnement, terrain, "Joueur1", 100, 10, 5, 0, 0, 1, 5);
    }

    @Test
    public void testAjouterPtsDeVie() {
        joueur.ajouterPtsDeVie(20);
        assertEquals(120, joueur.getPtsDeVie());
    }

    @Test
    public void testEstProche() {
        Monstre monstre = new Monstre(environnement, terrain, "Monstre1", 50, 0, 0, 25, 25, 2, 0);
        assertTrue(joueur.estProche(monstre));
    }

    @Test
    public void testAttaquer() {
        Monstre monstre = new Monstre(environnement, terrain, "Monstre1", 50, 0, 0, 0, 0, 2, 0);
        joueur.attaquer(monstre);
        assertEquals(40, monstre.getPtsDeVie());
    }

    @Test
    public void testDeplacer() {
        Skater skater = new Skater(environnement, terrain, "Skater1", 100, 0, 0, 5, 10);
        environnement.ajouterEntite(skater);
        double oldX = joueur.getPositionX();
        joueur.deplacer();
        assertEquals(oldX, joueur.getPositionX()); // Collision avec le Skater, la position ne change pas
    }

    @Test
    public void testAjouterVieSupplementaire() {
        joueur.ajouterVieSupplementaire(30);
        assertEquals(130, joueur.getPtsDeVie());
        assertEquals(30, joueur.getVieSupplementaire());
    }

    @Test
    public void testSetPtsDeVie() {
        joueur.setPtsDeVie(50);
        assertEquals(50, joueur.getPtsDeVie());
    }

    @Test
    public void testMortJoueur() {
        joueur.setPtsDeVie(0);
        assertTrue(joueur.estMortProperty().get());
    }
}
