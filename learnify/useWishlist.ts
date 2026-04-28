import { useState, useEffect } from "react";
import { supabase } from "@/integrations/supabase/client";
import { useAuth } from "@/contexts/AuthContext";
import { toast } from "@/hooks/use-toast";

export interface WishlistItem {
  id: string;
  course_id: string;
  courses: {
    id: string;
    title: string;
    instructor: string;
    image_url: string;
    price: number;
    original_price: number | null;
    rating: number;
    reviews_count: number;
  };
}

export const useWishlist = () => {
  const [wishlist, setWishlist] = useState<WishlistItem[]>([]);
  const [loading, setLoading] = useState(false);
  const { user } = useAuth();

  const fetchWishlist = async () => {
    if (!user) {
      setWishlist([]);
      return;
    }

    setLoading(true);

    const { data, error } = await supabase
      .from("wishlist")
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
          original_price,
          rating,
          reviews_count
        )
      `
      )
      .eq("user_id", user.id);

    if (error) {
      console.error("Fetch wishlist error:", error.message);
      setLoading(false);
      return;
    }

    if (data) setWishlist(data);

    setLoading(false);
  };

  const addToWishlist = async (courseId: string) => {
    if (!user) {
      toast({
        title: "Please sign in",
        description: "You need to be logged in to add items to your wishlist.",
        variant: "destructive",
      });
      return false;
    }

    const { error } = await supabase.from("wishlist").insert({
      user_id: user.id,
      course_id: courseId,
    });

    if (error) {
      if (error.code === "23505") {
        toast({
          title: "Already in wishlist",
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

    toast({ title: "Added to wishlist!" });
    fetchWishlist();
    return true;
  };

  const removeFromWishlist = async (courseId: string) => {
    if (!user) return;

    const { error } = await supabase
      .from("wishlist")
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

    toast({ title: "Removed from wishlist" });
    fetchWishlist();
  };

  const isInWishlist = (courseId: string) =>
    wishlist.some((item) => item.course_id === courseId);

  const toggleWishlist = async (courseId: string) => {
    if (isInWishlist(courseId)) {
      await removeFromWishlist(courseId);
    } else {
      await addToWishlist(courseId);
    }
  };

  useEffect(() => {
    fetchWishlist();
  }, [user]);

  return {
    wishlist,
    loading,
    addToWishlist,
    removeFromWishlist,
    isInWishlist,
    toggleWishlist,
    fetchWishlist,
  };
};
