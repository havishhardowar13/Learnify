import { useState, useEffect } from "react";
import { supabase } from "@/integrations/supabase/client";
import { useAuth } from "@/contexts/AuthContext";
import { toast } from "@/hooks/use-toast";

export interface CartItem {
  id: string;
  course_id: string;
  courses: {
    id: string;
    title: string;
    instructor: string;
    image_url: string;
    price: number;
    original_price?: number | null;
  };
}

export const useCart = () => {
  const [cart, setCart] = useState<CartItem[]>([]);
  const [loading, setLoading] = useState(false);
  const { user } = useAuth();

  const fetchCart = async () => {
    if (!user) {
      setCart([]);
      return;
    }

    setLoading(true);

    const { data, error } = await supabase
      .from("cart")
      .select(
        `
        id,
        course_id,
        courses (
          id,
          title,
          instructor,
          image_url,
          price,
          original_price
        )
      `
      )
      .eq("user_id", user.id);

    if (error) {
      console.error("Fetch cart error:", error.message);
      setLoading(false);
      return;
    }

    if (data) setCart(data);
    setLoading(false);
  };

  const addToCart = async (courseId: string) => {
    if (!user) {
      toast({
        title: "Please sign in",
        description: "You must be logged in to add items to your cart.",
        variant: "destructive",
      });
      return false;
    }

    const { error } = await supabase.from("cart").insert({
      user_id: user.id,
      course_id: courseId,
    });

    if (error) {
      if (error.code === "23505") {
        toast({
          title: "Already in cart",
          description: "This course is already added.",
        });
      } else {
        toast({
          title: "Error",
          description: error.message,
          variant: "destructive",
        });
      }
      return false;
    }

    toast({ title: "Added to cart!" });
    fetchCart();
    return true;
  };

  const removeFromCart = async (courseId: string) => {
    if (!user) return;

    const { error } = await supabase
      .from("cart")
      .delete()
      .eq("user_id", user.id)
      .eq("course_id", courseId);

    if (error) {
      toast({
        title: "Error",
        description: error.message,
        variant: "destructive",
      });
      return;
    }

    toast({ title: "Removed from cart" });
    fetchCart();
  };

  const isInCart = (courseId: string) =>
    cart.some((item) => item.course_id === courseId);

  useEffect(() => {
    fetchCart();
  }, [user]);

  return {
    cart,
    loading,
    addToCart,
    removeFromCart,
    isInCart,
    fetchCart,
  };
};
