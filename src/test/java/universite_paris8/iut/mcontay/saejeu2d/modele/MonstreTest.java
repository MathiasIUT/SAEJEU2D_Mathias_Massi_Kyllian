package universite_paris8.iut.mcontay.saejeu2d.modele;

import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

public class MonstreTest {
    private Environnement environnement;
    private Terrain terrain;
    private Monstre monstre;

    @BeforeEach
    public void setUp() {
        // Initialisation de l'environnement, du terrain et du monstre
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        environnement = new Environnement();
        monstre = new Monstre(environnement, terrain, "Monstre de test", 100, 10, 5, 50, 50, 1, 5);
    }

    @Test
    public void testInitialisationMonstre() {
        // Vérifie que le monstre est initialisé avec les valeurs correctes
        assertEquals("Monstre de test", monstre.getNom(), "Le nom du monstre doit être 'Monstre de test'");
        assertEquals(100, monstre.getPtsDeVie(), "Les points de vie du monstre doivent être 100");
        assertEquals(10, monstre.getPtsAttaque(), "Les points d'attaque du monstre doivent être 10");
        assertEquals(5, monstre.getPtsDefense(), "Les points de défense du monstre doivent être 5");
    }

    @Test
    public void testEnleverPV() {
        // Test de la perte de points de vie du monstre
        monstre.enleverPV(20);
        assertEquals(80, monstre.getPtsDeVie(), "Le monstre doit perdre 20 points de vie");
    }

    @Test
    public void testEstMort() {
        // Test de l'état de mort du monstre
        monstre.enleverPV(100);
        assertTrue(monstre.estMortProperty().get(), "Le monstre doit être mort après avoir perdu tous ses points de vie");
    }
}
