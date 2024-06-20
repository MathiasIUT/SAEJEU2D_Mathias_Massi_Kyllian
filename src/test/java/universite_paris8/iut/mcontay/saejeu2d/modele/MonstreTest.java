package universite_paris8.iut.mcontay.saejeu2d.modele;

import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

public class MonstreTest {
    private Monstre monstre;
    private Environnement environnement;
    private Terrain terrain;

    @BeforeEach
    public void setUp() {
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
        environnement = new Environnement();
        monstre = new Monstre(environnement, terrain, "Monstre1", 100, 10, 5, 50, 50, 1, 5);
    }

    @Test
    public void testEnleverPV() {
        monstre.enleverPV(20);
        assertEquals(80, monstre.getPtsDeVie());
    }

    @Test
    public void testEstMort() {
        monstre.enleverPV(100);
        assertTrue(monstre.estMortProperty().get());
    }
}
