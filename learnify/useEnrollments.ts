import { useState, useEffect } from "react";
import { supabase } from "@/integrations/supabase/client";
import { useAuth } from "@/contexts/AuthContext";
import { toast } from "@/hooks/use-toast";

export interface Enrollment {
  id: string;
  course_id: string;
  progress: number;
  enrolled_at: string;
  courses: {
    id: string;
    title: string;
    instructor: string;
    image_url: string;
    duration: string;
  };
}

export const useEnrollments = () => {
  const [enrollments, setEnrollments] = useState<Enrollment[]>([]);
  const [loading, setLoading] = useState(false);
  const { user } = useAuth();

  const fetchEnrollments = async () => {
    if (!user) {
      setEnrollments([]);
      return;
    }

    setLoading(true);

    const { data, error } = await supabase
      .from("enrollments")
      .select(
        `
        id,
        course_id,
        progress,
        enrolled_at,
        courses (
          id,
          title,
          instructor,
          image_url,
          duration
        )
      `
      )
      .eq("user_id", user.id);

    if (error) {
      console.error("Fetch enrollments error:", error.message);
      setLoading(false);
      return;
    }

    if (data) setEnrollments(data);

    setLoading(false);
  };

  const enroll = async (courseId: string) => {
    if (!user) {
      toast({
        title: "Please sign in",
        description: "You need to be logged in to enroll in a course.",
        variant: "destructive",
      });
      return false;
    }

    const { error } = await supabase.from("enrollments").insert({
      user_id: user.id,
      course_id: courseId,
    });

    if (error) {
      if (error.code === "23505") {
        toast({
          title: "Already enrolled",
          description: "You are already enrolled in this course.",
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

    toast({
      title: "Enrolled successfully!",
      description: "Start learning now.",
    });

    fetchEnrollments();
    return true;
  };

  const isEnrolled = (courseId: string) =>
    enrollments.some((e) => e.course_id === courseId);

  useEffect(() => {
    fetchEnrollments();
  }, [user]);

  return {
    enrollments,
    loading,
    enroll,
    isEnrolled,
    fetchEnrollments,
  };
};
