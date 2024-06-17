//import static org.junit.jupiter.api.Assertions.*;
//import org.junit.jupiter.api.BeforeEach;
//import org.junit.jupiter.api.Test;
//
//public class InventaireTest {
//    private Inventaire inventaire;
//    private Epee epee;
//    private Bouclier bouclier;
//
//    @BeforeEach
//    public void setUp() {
//        inventaire = new Inventaire();
//        epee = new Epee("Épée", "Une épée tranchante", 20, "/path/to/image.png", 0, 0);
//        bouclier = new Bouclier("Bouclier", "Un bouclier robuste", null, 5, "/path/to/image.png", 0, 0, 30);
//    }
//
//    @Test
//    public void testAjouterObjet() {
//        inventaire.ajouterObjet(epee);
//        assertTrue(inventaire.contientObjet(epee));
//    }
//
//    @Test
//    public void testRetirerObjet() {
//        inventaire.ajouterObjet(epee);
//        inventaire.retirerObjet(epee);
//        assertFalse(inventaire.contientObjet(epee));
//    }
//
//    @Test
//    public void testContientObjet() {
//        inventaire.ajouterObjet(epee);
//        assertTrue(inventaire.contientObjet(epee));
//    }
//}
