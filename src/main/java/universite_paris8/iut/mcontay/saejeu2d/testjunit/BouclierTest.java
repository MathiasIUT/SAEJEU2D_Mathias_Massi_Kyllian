//import static org.junit.jupiter.api.Assertions.*;
//import org.junit.jupiter.api.BeforeEach;
//import org.junit.jupiter.api.Test;
//
//public class BouclierTest {
//    private Bouclier bouclier;
//
//    @BeforeEach
//    public void setUp() {
//        bouclier = new Bouclier("Bouclier", "Un bouclier robuste", null, 5, "/path/to/image.png", 0, 0, 30);
//    }
//
//    @Test
//    public void testDecrementerUtilisations() {
//        bouclier.decrementerUtilisations();
//        assertEquals(4, bouclier.getUtilisationsRestantes());
//        bouclier.decrementerUtilisations();
//        bouclier.decrementerUtilisations();
//        bouclier.decrementerUtilisations();
//        bouclier.decrementerUtilisations();
//        assertEquals(0, bouclier.getUtilisationsRestantes());
//    }
//
//    @Test
//    public void testGetBarreVieSupplementaire() {
//        assertEquals(30, bouclier.getBarreVieSupplementaire());
//    }
//}