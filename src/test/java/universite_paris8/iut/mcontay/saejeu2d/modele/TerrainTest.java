package universite_paris8.iut.mcontay.saejeu2d.modele;

import static org.junit.jupiter.api.Assertions.*;
import org.junit.jupiter.api.BeforeEach;
import org.junit.jupiter.api.Test;

public class TerrainTest {
    private Terrain terrain;

    @BeforeEach
    public void setUp() {
        // Initialisation du terrain avec le chemin du fichier JSON
        terrain = new Terrain("/universite_paris8/iut/mcontay/saejeu2d/jsonmapsurfacefinal.json");
    }

    @Test
    public void testTerrainLoaded() {
        // Vérifie que le terrain est chargé et n'est pas null
        assertNotNull(terrain, "Le terrain doit être chargé et ne pas être null");
    }

    @Test
    public void testEstAutorisee() {
        // Coordonnées d'exemple - ajustez ces valeurs en fonction de la logique spécifique de votre terrain
        assertTrue(terrain.estAutorisee(32, 32), "La position (32, 32) devrait être autorisée");
        assertFalse(terrain.estAutorisee(-1, -1), "La position (-1, -1) ne devrait pas être autorisée");
    }
}
